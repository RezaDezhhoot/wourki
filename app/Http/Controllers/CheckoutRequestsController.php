<?php

namespace App\Http\Controllers;

use App\AccountingDocuments;
use App\Checkout;
use App\CheckoutRequests;
use App\Events\CheckoutStore;
use App\Libraries\Swal;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\Store;
use App\User;
use App\Wallet;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;
use Throwable;

class CheckoutRequestsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Factory|Application|View
     */
    public function index(Request $request)
    {
        $requests = CheckoutRequests::with('user')->when($request->user, function ($q) use ($request) {
            $q->where('user_id', $request->user);
        })->when(function ($collection) use ($request) {
            return $request->status;
        }, function ($q) use ($request) {
            $q->where('status', $request->status);
        })->orderBy('id', 'desc')->paginate(20);

        $users = User::select(['first_name', 'last_name', 'id'])->get();

        foreach ($requests as $r) {
            $r->user->load('store');
        }

        return view('admin.checkoutRequest.index', compact(['requests', 'users']));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(Request $request): RedirectResponse
    {
        $authUserId = auth()->id();

        $user = User::with(['wallet' => function ($q) {
            $q->where('payable', 1);
        }, 'checkoutRequest' => function ($q) {
            $q->where('status', 0);
        }])->where('id', $authUserId)->first();

        $totalPositive = $user->wallet->sum('cost');
        $totalReduced = 0;

        foreach ($user->wallet as $wallet) {
            $wallet->load('reducedFrom');
            if (!is_null($wallet->reducedFrom)) {
                foreach ($wallet->reducedFrom as $reducedFrom) {
                    $totalReduced += $reducedFrom->pivot->Amount;
                }
            }
        };

        if (is_null($user->wallet)) {
            Swal::error('اخطار', 'مبلغ قابل برداشت در کیف پول شما وجود ندارد');
            return redirect()->back();
        }


        if ($totalPositive <= $totalReduced) {
            Swal::error('اخطار', 'مبلغ قابل برداشت در کیف پول شما وجود ندارد');
            return redirect()->back();
        }

        if (count($user->checkoutRequest)) {
            Swal::error('اخطار', 'درخواست تعیین تکلیف نشده ای از شما وجود دارد،لطفا منتظر بمانید');
            return redirect()->back();
        }

        try {
            $commit = \DB::transaction(function () use ($authUserId, $user) {
                $user->checkoutRequest()->create([
                    'approval' => 0,
                    'checkout_id' => 0
                ]);

                return true;
            });
        } catch (\Exception $e) {
            Swal::error('اخطار', 'خطای برنامه نویسی .با پشتیبان تماس بگیرید');

        }
        if ($commit == true) {
            Swal::success('درخواست', 'درخواست با موفقیت ثبت شد');

        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param CheckoutRequests $checkoutRequests
     * @return Response
     */
    public function show(CheckoutRequests $checkoutRequests)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param CheckoutRequests $checkoutRequests
     * @return Application|Factory|View
     */
    public function edit(CheckoutRequests $checkoutRequests)
    {
        $checkoutRequests->load('user');
        $checkoutRequests->load('checkout');

        $checkoutRequests->user->load(['wallet' => function ($q) {
            $q->where('payable', 1);
        }]);

        $positiveTotal = $checkoutRequests->user->wallet->sum('cost');
        $negativeTotal = 0;

        foreach ($checkoutRequests->user->wallet as $wallet) {
            $wallet->load('reducedFrom');
            foreach ($wallet->reducedFrom as $reducedFrom) {
                $negativeTotal += $reducedFrom->pivot->Amount;
            }
        }

        return view('admin.checkoutRequest.edit', compact(['checkoutRequests', 'positiveTotal', 'negativeTotal']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param CheckoutRequests $checkoutRequests
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(Request $request, CheckoutRequests $checkoutRequests)
    {
        $request->validate([
            'price' => 'required|numeric',
            'pay_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
        ], [
            'pay_id.required' => 'شماره پیگیری الزامی است.',
            'price.required' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت نامعتبر است.',
            'date.required' => 'انتخاب تاریخ الزامی است.',
            'date.date_format' => 'فرمت تاریخ نامعتبر است.',
        ]);

        $checkoutRequests->load('user');
        $checkoutRequests->load('checkout');
        $checkoutRequests->user->load('store');


//        try {
        $commit = \DB::transaction(function () use ($checkoutRequests, $request) {

            if (!is_null($checkoutRequests->checkout)) {
                $checkoutRequests->checkout->load('wallet');

                $checkoutRequests->checkout->wallet->reducedItem()->detach(); //wallet reduce delete
                $checkoutRequests->checkout->wallet()->delete(); // wallet delete
                $checkoutRequests->checkout->accounting()->delete(); //checkout accounting document delete
                $checkoutRequests->checkout()->delete(); //  checkout delete
            }

            $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('d/m/Y', $request->date)->format('Y-m-d');
            $todayDate = Jalalian::forge('now')->format('%d %B %Y');
            $todayHour = Jalalian::forge('now')->format('H:i');


            //create wallet
            $wallet = Wallet::create([
                'user_id' => $checkoutRequests->user->id,
                'cost' => -1 * $request->price,
                'wallet_type' => 'output'
            ]);


            //wallet reduce
            $walletHandler = new WalletHandler();
            if ($data = $walletHandler->NegativeRecordReducer($wallet, 'checkout', $checkoutRequests->user->id)) {
                $wallet->reducedItem()->attach($data);
            } else {
                Swal::error('موجودی ناکافی', 'مبلغ تسویه بیشتر از مبلغ قابل پرداخت به فروشنده میباشد');
                return false;
            }

            $checkoutData = [
                'user_id' => $checkoutRequests->user->id,
                'wallet_id' => $wallet->id,
                'price' => $request->price,
                'pay_id' => $request->pay_id,
                'created_at' => $date,
                'updated_at' => $date
            ];

            if (!is_null($checkoutRequests->user->store)) {
                $checkoutData['store_id'] = $checkoutRequests->user->store->id;

            }

            //create checkout
            $checkout = Checkout::create($checkoutData);
            $checkout->load('store');
            $name = $checkout->store ? $checkout->store->name : $checkoutRequests->user->first_name . ' ' . $checkoutRequests->user->last_name;


            //create accounting doc
            AccountingDocuments::create([
                'balance' => $checkout->price,
                'description' => 'تسویه حساب ' . $name . ' به مبلغ ' . $checkout->price . ' - شماره پیگیری ' . $checkout->pay_id .
                    ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour,
                'checkout_id' => $checkout->id,
                'wallet_id' => $wallet->id,
                'type' => 'checkout'
            ]);

            //update checkout request
            $checkoutRequests->update([
                'checkout_id' => $checkout->id,
                'status' => 1
            ]);

            event(new CheckoutStore($checkout->store ?? $checkoutRequests->user));

            return true;
        });

//        } catch (\Exception $e) {
//            Swal::success('خطای سیستمی', 'لطفا با پشتیبان تماس بگیرید');
//            return redirect()->back();
//        }

        if ($commit == true) {
            Swal::success('موفقیت آمیز بودن تسویه حساب.', 'تسویه حساب با موفقیت ایجاد شد.');
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CheckoutRequests $checkoutRequests
     * @return RedirectResponse
     * @throws Throwable
     */
    public function destroy(Request $request, CheckoutRequests $checkoutRequests): RedirectResponse
    {
        $checkoutRequests->load(['checkout.wallet.reducedItem']);
        try {
            $commit = \DB::transaction(function () use ($checkoutRequests, $request) {

                //for delete and reject
                if (!is_null($checkoutRequests->checkout)) {
//                dd($checkoutRequests->checkout->wallet());
                    $status = 0;
                    $checkoutRequests->checkout->wallet->reducedItem()->detach(); //wallet reduce delete
                    $checkoutRequests->checkout->wallet->delete(); // wallet delete
                    $checkoutRequests->checkout->accounting->delete(); //checkout accounting document delete
                    $checkoutRequests->checkout()->delete(); //  checkout delete

                }

                if ($request->filled('reject')) {
                    $status = 2;
                }

                $checkoutRequests->update(
                    [
                        'checkout_id' => null,
                        'status' => $status,
                    ]
                );

                return true;
            });

        } catch (\Exception $e) {
            Swal::success('خطای سیستمی', 'لطفا با پشتیبان تماس بگیرید');
            return redirect()->back();
        }

        if ($commit == true and $request->filled('reject')) {
            Swal::success('موفق', 'رد درخواست تسویه با موفقیت انجام شد');
        } else {
            Swal::success('موفق', 'حذف تسویه فروشگاه با موفقیت انجام شد');
        }
        return redirect()->back();

    }
}
