<?php

namespace App\Http\Controllers;

use App\AccountingDocuments;
use App\Bill;
use App\BillItem;
use App\Checkout;
use App\Exports\CreditStoresListExport;
use App\Libraries\Swal;
use App\Marketer;
use App\Plan;
use App\PlanSubscription;
use App\Province;
use App\PurchaseProducts\Documents\DocumentHandler;
use App\Store;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class AccountingDocumentsController extends Controller
{

    public function exportCreditStores()
    {
        return Excel::download(new CreditStoresListExport, 'stores-export.xlsx');
    }

    public function index(Request $request)
    {
        $stores = Store::where('status', 'approved')->select('id', 'name')->get();
        $plans = Plan::all();
        $provinces = Province::all();

        if ($request->document_type == 'checkout') {
            $lists = $this->getCheckoutDocuments();
            $lists = $lists
                ->where('accounting_documents.checkout_id', '!=', 0)
                ->select('store.name as store_name', 'accounting_documents.description', 'accounting_documents.balance as balance',
                    'accounting_documents.created_at as accounting_documents_created_at', 'checkouts.created_at as checkouts_created_at', 'checkouts.pay_id')
                ->orderBy('accounting_documents.created_at', 'desc')
                ->selectRaw('concat(users.first_name , " " , users.last_name) as full_name');
            if ($request->filled('store')) {
                $lists->where('store.id', $request->store);
            }
            if ($request->filled('checkout_date_range')) {
                $startDate = Carbon::createFromTimestamp($request->start_date_checkout)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_checkout)->toDateTimeString();
                $lists->where('checkouts.created_at', '>=', $startDate)
                    ->where('checkouts.created_at', '<=', $endDate);
            }
            if ($request->filled('doc_date_range')) {
                $startDate = Carbon::createFromTimestamp($request->start_date_doc)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_doc)->toDateTimeString();
                $lists->where('accounting_documents.created_at', '>=', $startDate)
                    ->where('accounting_documents.created_at', '<=', $endDate);
            }
            $lists = $lists->paginate(20)->appends($request->all());
            return view('admin.accounts_document.index', compact('stores', 'provinces', 'lists'));
        } elseif ($request->document_type == 'marketer') {
            $lists = $this->getMarketerDocuments();
            $marketers = Marketer::select('marketer.*')
                ->addSelect(DB::raw('(
                    select concat(first_name , " " , last_name)
                    from users
                    where users.id = marketer.user_id
                ) as full_name'))
                ->get();
            $lists = $lists
                ->where('accounting_documents.marketer_id', '!=', 0)
                ->select('accounting_documents.balance', 'accounting_documents.description', 'accounting_documents.type',
                    'accounting_documents.created_at as accounting_documents_created_at', 'checkouts.created_at as checkouts_created_at', 'checkouts.pay_id')
                ->orderBy('accounting_documents.created_at', 'desc')
                ->selectRaw('concat(users.first_name , " " , users.last_name) as full_name');
            if ($request->filled('marketer')) {
                $lists->where('marketer.id', $request->marketer);
            }
            if ($request->filled('checkout_date_range')) {
                $startDate = Carbon::createFromTimestamp($request->start_date_checkout)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_checkout)->toDateTimeString();
                $lists->where('checkouts.created_at', '>=', $startDate)
                    ->where('checkouts.created_at', '<=', $endDate);
            }
            if ($request->filled('doc_date_range')) {
                $startDate = Carbon::createFromTimestamp($request->start_date_doc)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_doc)->toDateTimeString();
                $lists->where('accounting_documents.created_at', '>=', $startDate)
                    ->where('accounting_documents.created_at', '<=', $endDate);
            }
            $lists = $lists->paginate(20)->appends($request->all());
            return view('admin.accounts_document.index', compact('marketers', 'lists'));
        } elseif ($request->document_type == 'bill') {
            $totalBalance = 0;
            if ($request->filled('bill_store')) {
                $storeName = Store::find($request->bill_store)->name;
                $lists = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
                    ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
                    ->where('accounting_documents.type', '!=', 'plan')
                    ->orderBy('accounting_documents.created_at', 'desc')
                    ->where(function ($query) use ($request) {
                        $query->where('bill.store_id', $request->bill_store)
                            ->orWhere('checkouts.store_id', $request->bill_store);
                    })
                    ->select('accounting_documents.*', 'bill.pay_id')
                    ->paginate(15);

                $sumBills = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
                    ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
                    ->where('bill.status', '=', 'delivered')
                    ->where('accounting_documents.type', '=', 'bill')
                    ->where(function ($query) use ($request) {
                        $query->where('bill.store_id', $request->bill_store)
                            ->orWhere('checkouts.store_id', $request->bill_store);
                    })
                    ->select('accounting_documents.balance')
                    ->sum('balance');

                $sumCheckouts = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
                    ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
                    ->where('accounting_documents.type', '=', 'checkout')
                    ->where(function ($query) use ($request) {
                        $query->where('bill.store_id', $request->bill_store)
                            ->orWhere('checkouts.store_id', $request->bill_store);
                    })
                    ->select('accounting_documents.balance')
                    ->sum('balance');

                $totalBalance = $sumBills - $sumCheckouts;
            } else {
                $lists = $this->getBillDocuments();
                $lists = $lists
                    ->where('accounting_documents.bill_id', '!=', 0)
                    ->select('store.name as store_name', 'accounting_documents.description', 'accounting_documents.created_at as accounting_documents_created_at', 'bill.pay_type',
                        'bill.created_at as bill_created_at', 'bill.pay_id', 'bill.id as bill_id', 'store.slug', 'bill.pay_type', 'accounting_documents.type', 'accounting_documents.balance')
                    ->orderBy('accounting_documents.created_at', 'desc')
                    ->selectRaw('concat(users.first_name , " " , users.last_name) as full_name');

                if ($request->filled('checkout_date_range')) {
                    $startDate = Carbon::createFromTimestamp($request->start_date_checkout)->toDateTimeString();
                    $endDate = Carbon::createFromTimestamp($request->end_date_checkout)->toDateTimeString();
                    $lists->where('bill.created_at', '>=', $startDate)
                        ->where('bill.created_at', '<=', $endDate);
                }
                if ($request->filled('doc_date_range')) {
                    $startDate = Carbon::createFromTimestamp($request->start_date_doc)->toDateTimeString();
                    $endDate = Carbon::createFromTimestamp($request->end_date_doc)->toDateTimeString();
                    $lists->where('accounting_documents.created_at', '>=', $startDate)
                        ->where('accounting_documents.created_at', '<=', $endDate);
                }
                if ($request->filled('pay_type') && $request->pay_type == 'online') {
                    $lists->where('bill.status', $request->pay_type);
                }
                $lists = $lists->paginate(20)->appends($request->all());
            }

            $billItem = new BillItem();
            foreach ($lists as $index => $item) {
                $lists[$index]->bill_price = $billItem->getBillItemPrice($item->bill_id);
            }

            return view('admin.accounts_document.index', compact('stores', 'provinces', 'lists', 'totalBalance', 'storeName'));
        } elseif ($request->document_type == 'plan') {
            $lists = $this->getPlanDocuments();
            $lists = $lists
                ->orderBy('accounting_documents.created_at', 'desc')
                ->where('accounting_documents.plan_id', '!=', 0)
                ->select('store.name as store_name', 'accounting_documents.description', 'accounting_documents.created_at as accounting_documents_created_at',
                    'plan.created_at as plan_created_at', 'plan.pay_id', 'plan.id as plan_id', 'users.first_name', 'users.last_name')
                ->join('seller_plan_subscription_details', 'seller_plan_subscription_details.id', '=', 'accounting_documents.plan_id')
                ->join('seller_plans', 'seller_plans.id', '=', 'seller_plan_subscription_details.plan_id')
                ->groupBy('store.name', 'accounting_documents.description', 'accounting_documents.created_at',
                    'plan.created_at', 'plan.pay_id', 'plan.id', 'users.first_name', 'users.last_name');
            if ($request->filled('plan')) {

                $lists->where('seller_plan_subscription_details.plan_id', $request->plan);
            }
            if ($request->filled('checkout_date_range')) {/*buy*/
                $startDate = Carbon::createFromTimestamp($request->start_date_checkout)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_checkout)->toDateTimeString();
                $lists->where('plan.created_at', '>=', $startDate)
                    ->where('plan.created_at', '<=', $endDate);
            }
            if ($request->filled('doc_date_range')) {/*create account*/
                $startDate = Carbon::createFromTimestamp($request->start_date_doc)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_doc)->toDateTimeString();
                $lists->where('accounting_documents.created_at', '>=', $startDate)
                    ->where('accounting_documents.created_at', '<=', $endDate);
            }
            $lists = $lists->paginate(20)->appends($request->all());

            foreach ($lists as $index => $item) {
                $sub = PlanSubscription::find($item->plan_id)->plan;
                $lists[$index]->planName = $sub->name;
                $lists[$index]->planMonth = $sub->month_inrterval;
                $lists[$index]->planPrice = $sub->price;
            }
            return view('admin.accounts_document.index', compact('plans', 'lists'));
        } elseif ($request->document_type == 'wallet') {
            $lists = $this->getWalletDocuments();
            $users = User::join('wallet', 'wallet.user_id', '=', 'users.id')
                ->select('users.*')
                ->get();
            $lists = $lists
                ->where('accounting_documents.wallet_id', '!=', 0)
                ->select('accounting_documents.description', 'accounting_documents.created_at as accounting_documents_created_at', 'wallet.cost as planPrice',
                    'wallet.created_at as wallet_created_at', 'wallet.tracking_code as pay_id', 'wallet.id as plan_id', 'accounting_documents.type', 'accounting_documents.balance')
                ->orderBy('accounting_documents.created_at', 'desc')
                ->selectRaw('concat(users.first_name , " " , users.last_name) as full_name');
            if ($request->filled('user')) {
                $lists->where('users.id', $request->user);
            }
            if ($request->filled('checkout_date_range')) {/*buy*/
                $startDate = Carbon::createFromTimestamp($request->start_date_checkout)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_checkout)->toDateTimeString();
                $lists->where('wallet.created_at', '>=', $startDate)
                    ->where('wallet.created_at', '<=', $endDate);
            }
            if ($request->filled('doc_date_range')) {/*create account*/
                $startDate = Carbon::createFromTimestamp($request->start_date_doc)->toDateTimeString();
                $endDate = Carbon::createFromTimestamp($request->end_date_doc)->toDateTimeString();
                $lists->where('wallet.created_at', '>=', $startDate)
                    ->where('wallet.created_at', '<=', $endDate);
            }
            $lists = $lists->paginate(20)->appends($request->all());

            return view('admin.accounts_document.index', compact('lists', 'users'));
        }
        $lists = AccountingDocuments::where('accounting_documents.type', '!=', 'plan')
            ->select('accounting_documents.*')
            ->orderBy('accounting_documents.created_at', 'desc')
            ->paginate(20)->appends($request->all());
        return view('admin.accounts_document.index', compact('lists'));

    }

    private function getCheckoutDocuments()
    {
        $list = AccountingDocuments::join('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
            ->join('store', 'store.id', '=', 'checkouts.store_id')
            ->join('users', 'users.id', '=', 'store.user_id');
        return $list;
    }

    private function getBillDocuments()
    {
        $list = AccountingDocuments::join('bill', 'bill.id', '=', 'accounting_documents.bill_id')
            ->join('store', 'store.id', '=', 'bill.store_id')
            ->join('users', 'users.id', '=', 'bill.user_id');
        return $list;
    }

    private function getPlanDocuments()
    {
        $list = AccountingDocuments::join('seller_plan_subscription_details as plan', 'plan.id', '=', 'accounting_documents.plan_id')
            ->join('users', 'users.id', '=', 'plan.user_id')
            ->join('store', 'store.user_id', '=', 'users.id');
        return $list;
    }

    private function getWalletDocuments()
    {
        $list = AccountingDocuments::join('wallet', 'wallet.id', '=', 'accounting_documents.wallet_id')
            ->join('users', 'users.id', '=', 'wallet.user_id');
        return $list;
    }

    private function getMarketerDocuments()
    {
        $list = AccountingDocuments::join('checkouts', 'checkouts.id', '=', 'accounting_documents.marketer_id')
            ->join('users', 'users.id', '=', 'checkouts.marketer_id')
            ->join('marketer', 'marketer.user_id', '=', 'users.id');
        return $list;
    }

    public function submitPlanDocument(Request $request)
    {
        $request->validate([
            'planId.*' => 'numeric',
            'planId' => 'required|array|exists:seller_plan_subscription_details,id'
        ], [
            'planId.*.numeric' => 'گزینه انتخابی نامعتبر است.',
            'planId.required' => 'هیچ موردی برای ثبت انتخاب نشده است.',
            'planId.exists' => 'گزینه انتخابی نامعتبر است.',
            'planId.array' => 'گزینه انتخابی نامعتبر است.',
        ]);
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');
        foreach ($request->planId as $item) {
            $planId = PlanSubscription::find($item);
            $planPrice = $planId->plan->price;
            $planName = $planId->plan->plan_name;
            $userName = $planId->user->name;

            $accounting_documents = new AccountingDocuments();
            $accounting_documents->balance = $planPrice;
            $accounting_documents->description = 'خرید صورتحساب ' . $planName . ' توسط ' . $userName . ' به شماره پیگیری ' . $planId->pay_id . ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour;
            $accounting_documents->plan_id = $planId->id;
            $accounting_documents->type = 'plan';
            $accounting_documents->save();
        }
        Swal::success('موفقیت آمیز بودن ثبت سند.', 'ثبت اسناد با موفقیت انجام شد.');
        return redirect()->back();
    }

    /**
     * @throws \Throwable
     */
    public function submitBillDocument(Request $request): RedirectResponse
    {
        $request->validate([
            'billId' => 'required|array',
            'billId.*' => 'numeric|exists:bill,id'
        ], [
            'billId.*.numeric' => 'گزینه انتخابی نامعتبر است.',
            'billId.required' => 'هیچ موردی برای ثبت انتخاب نشده است.',
            'billId.exists' => 'گزینه انتخابی نامعتبر است.',
            'billId.array' => 'گزینه انتخابی نامعتبر است.',
        ]);

        try {
            $commit = DB::transaction(function () use ($request) {
                $bills = Bill::whereIn('id', $request->input('billId'))->with('billItems')->get();
                $result = new DocumentHandler($bills);
                $result->submitBillDocument();
                return true;
            });

        } catch (\Exception $e) {
            Swal::success('اخطار', 'خطا در ثبت با پشتیبان تماس بگیرید.');
        }

        if ($commit == true) {
            Swal::success('موفقیت آمیز.', 'ثبت اسناد با موفقیت انجام شد.');
        }

        return redirect()->back();
    }

    public function submitStoreCheckoutDocument(Request $request)
    {
        $request->validate([
            'checkoutId.*' => 'numeric',
            'checkoutId' => 'required|array|exists:checkouts,id'
        ], [
            'checkoutId.*.numeric' => 'گزینه انتخابی نامعتبر است.',
            'checkoutId.required' => 'هیچ موردی برای ثبت انتخاب نشده است.',
            'checkoutId.exists' => 'گزینه انتخابی نامعتبر است.',
            'checkoutId.array' => 'گزینه انتخابی نامعتبر است.',
        ]);
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');
        foreach ($request->checkoutId as $item) {
            $checkout = Checkout::find($item);
            $storeName = Checkout::find($item)->store->name;
            $storePrice = $checkout->price;

            $accounting_documents = new AccountingDocuments();
            $accounting_documents->balance = $storePrice;
            $accounting_documents->description = 'تسویه حساب ' . $storeName . ' به مبلغ ' . $storePrice . ' - شماره پیگیری ' . $checkout->pay_id .
                ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour;
            $accounting_documents->checkout_id = $checkout->id;
            $accounting_documents->type = 'checkout';
            $accounting_documents->save();
        }
        Swal::success('موفقیت آمیز.', 'ثبت اسناد با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function checkoutRequest()
    {
        $user = User::with('wallet')->where('id', auth()->id())->first();

    }

    public function submitWalletDocument(Request $request)
    {
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');
        foreach ($request->walletId as $item) {
            $wallet = Wallet::find($item);
            $walletName = User::find($wallet->user_id)->first_name . ' ' . User::find($wallet->user_id)->last_name;
            $walletPrice = $wallet->cost;

            $accounting_documents = new AccountingDocuments();
            $accounting_documents->balance = $walletPrice;
            $accounting_documents->description = 'شارژ کیف ' . $walletName . ' پول به مبلغ ' . $walletPrice . ' - شماره پیگیری ' . $wallet->pay_id .
                ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour;
            $accounting_documents->wallet_id = $wallet->id;
            $accounting_documents->type = 'wallet';
            $accounting_documents->save();
        }
        Swal::success('موفقیت آمیز.', 'ثبت اسناد با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function submitMarketerCheckoutDocument(Request $request)
    {
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');
        foreach ($request->checkoutId as $item) {
            $checkout = Checkout::find($item);
            $marketer = Marketer::where('user_id', $checkout->marketer_id)->first();
            $marketerName = User::where('id', $marketer->user_id)->first()->first_name . ' ' . User::where('id', $marketer->user_id)->first()->last_name;
            $marketerPrice = $checkout->price;

            $accounting_documents = new AccountingDocuments();
            $accounting_documents->balance = $marketerPrice;
            $accounting_documents->description = 'تسویه حساب ' . $marketerName . ' به مبلغ ' . $marketerPrice . ' - شماره پیگیری ' . $checkout->pay_id .
                ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour;
            $accounting_documents->marketer_id = $checkout->id;
            $accounting_documents->type = 'marketer';
            $accounting_documents->save();
        }
        Swal::success('موفقیت آمیز.', 'ثبت اسناد با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function userAccountingDocument(Request $request)
    {
        $user = auth()->guard('web')->user();
        $store = Store::where('user_id' , $user->id)->where('store_type' , $request->query('store_type') ? $request->query('store_type') : 'product')->first();
        $lists = $totalBalance = null;
       if ($store) {
        $lists = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
            ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
            ->whereNotIn('accounting_documents.type', ['upgrade' , 'plan' , 'ad'])
            ->where(function ($query) use ( $store, $user) {
                $query->when(function ($collection) use ($store) {
                    return isset($store) && !is_null($store);
                }, function ($query) use ($store) {
                    $query->where('bill.store_id', $store->id)
                        ->orWhere('checkouts.store_id', $store->id)
                        ->orWhere('accounting_documents.market_id' , $store->id);
                });
            })
            ->select('accounting_documents.*', 'bill.pay_id as billPayID', 'checkouts.pay_id as checkoutPayID')
            ->get();
        $sumBills = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
            ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
            ->whereIn('accounting_documents.type', ['bill' , 'commission'])
            // ->where('bill.status', '=', 'delivered')
            ->where(function ($query) use ($request, $store, $user) {
                $query->when(function ($collection) use ($store) {
                    return isset($store) && !is_null($store);
                }, function ($query) use ($store) {
                    $query->where('bill.store_id', $store->id)
                        ->orWhere('checkouts.store_id', $store->id)
                        ->orWhere('accounting_documents.market_id', $store->id);
                });
            })
            ->select('accounting_documents.balance')
            ->sum('balance');

        $sumCheckouts = AccountingDocuments::leftjoin('checkouts', 'checkouts.id', '=', 'accounting_documents.checkout_id')
            ->leftjoin('bill', 'bill.id', '=', 'accounting_documents.bill_id')
            ->where('accounting_documents.type', '=', 'checkout')
            ->where(function ($query) use ($request, $store, $user) {
                $query->when(function ($collection) use ($store) {
                    return isset($store) && !is_null($store);
                }, function ($query) use ($store) {
                    $query->where('bill.store_id', $store->id)
                        ->orWhere('checkouts.store_id', $store->id);
                });
                    // ->orWhere('checkouts.user_id', $user->id);

            })
            ->select('accounting_documents.balance')
            ->sum('balance');

        $totalBalance = $sumBills - $sumCheckouts;

       }

        return view('frontend.my-account.checkout.index', compact('lists', 'totalBalance', 'store'));
    }
}
