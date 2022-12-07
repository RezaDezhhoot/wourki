<?php

namespace App\Http\Controllers;

use App\AccountingDocuments;
use App\Checkout;
use App\Libraries\Swal;
use App\PurchaseProducts\Exceptions\WalletNotReduced;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\ReagentCode;
use App\User;
use App\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;
use Larabookir\Gateway\Mellat\Mellat;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $users = User::join('wallet', 'wallet.user_id', '=', 'users.id')
            ->select('users.*')
            ->distinct('users.*')
            ->where('users.banned', 0)
            ->get();
        $wallets = Wallet::join('users', 'users.id', '=', 'wallet.user_id')
            ->select('wallet.*', 'users.first_name', 'users.last_name')
            ->where('tracking_code', '!=', null)
            ->whereNotIn('wallet.id', AccountingDocuments::where('wallet_id', '!=', 0)->select('wallet_id')->get()->toArray())
            ->latest();
        if ($request->has('user')) {
            $wallets->where('user_id', $request->user);
        }
        $wallets = $wallets->paginate(15);
        return view('admin.wallet.list', compact('wallets', 'users'));
    }

    public function userWallet(User $user)
    {
        $wallets = Wallet::join('users', 'users.id', '=', 'wallet.user_id')
            ->where('wallet.user_id', $user->id)
            ->select('users.*', 'wallet.*', 'wallet.created_at as wallet_created_date')
            ->paginate(15);
        $sumWallet = Wallet::where('user_id', $user->id)->sum('cost');
        return view('admin.wallet.index', compact('wallets', 'user', 'sumWallet'));
    }

    public function marketerWallet(User $user, Request $request)
    {
        $wallets = ReagentCode::where('reagent_code', $user->reagent_code)
            ->select('reagent_code.*')
            ->addSelect(DB::raw('(
                select concat(first_name , " " , last_name)
                from users
                where users.id = reagent_code.user_id
            ) as reagent_user'));

        if ($request->filled('checkout') && $request->checkout != 'both') {
            if ($request->checkout == 'isCheckout') $wallets->where('checkout', 1)->where('checkout', '!=', null);
            elseif ($request->checkout == 'notCheckout') $wallets->where('checkout', 0);
        } elseif (!$request->filled('checkout')) {
            $wallets->where('checkout', 0);
        }
        $wallets = $wallets->paginate(15);

        $sumWallet = ReagentCode::where('reagent_code', $user->reagent_code)
            ->where('checkout', 0)
            ->sum('reagent_user_fee');
        return view('admin.wallet.marketerWallet', compact('wallets', 'user', 'sumWallet'));
    }

    /**
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $commit = '';
        $walletHandler = new WalletHandler();
        $type = 'input';
        $reduce = false;
        if ((int)$request->cost < 0) {
            $type = 'output';
            $reduce = true;
        }
        try {
            $commit = DB::transaction(function () use ($request, $walletHandler, $type, $reduce) {
                $wallet = Wallet::create([
                    'user_id' => $request->user,
                    'cost' => $request->cost,
                    'wallet_type' => $type,
                ]);
                if ($reduce) {
                    if ($data = $walletHandler->NegativeRecordReducer($wallet, '', $request->user)) {
                        $wallet->reducedItem()->attach($data);
                    } else {
                        throw new WalletNotReduced;
                    }
                }
                return true;
            });
        } catch (WalletNotReduced $e) {
            Swal::error('موجودی ناکافی', 'موجودی ناکافی است');
        };
        if ($commit == true) {
            Swal::success('موفق', 'موجودی کیف پول با موفقیت تغییر کرد');
        }

        return back();
    }

    public function userCharge(Request $request)
    {
        $request->validate([
            'cost' => 'required|numeric',
        ]);
        $request->session()->put('cost', $request->cost);
        $gateway = Gateway::zarinpal();
        $gateway->setCallback(route('verify.wallet'));
        $gateway
            ->price($request->cost * 10)
            ->ready();

        return $gateway->redirect();
    }

    public function verifyWallet(Request $request)
    {
        try {
            $gateway = Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            Wallet::create([
                'user_id' => auth()->guard('web')->user()->id,
                'cost' => $request->session()->get('cost'),
                'wallet_type' => 'input',
                'tracking_code' => $trackingCode,
            ]);
            Swal::success('تبریک!', 'شارژ کیف پول شما با موفقیت انجام شد.');
            return redirect()->route('wallet.index');
        } catch (RetryException $e) {
            echo $e->getMessage();
        } catch (PortNotFoundException $e) {
            echo $e->getMessage();
        } catch (InvalidRequestException $e) {
            echo $e->getMessage();
        } catch (NotFoundTransactionException $e) {
            echo $e->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkout(Request $request)
    {
        $user = User::find($request->user);
        $marketerPrice = ReagentCode::where('reagent_code', $user->reagent_code)
            ->where('checkout', 0)
            ->sum('reagent_user_fee');

        Checkout::create([
            'price' => $marketerPrice,
            'marketer_id' => $user->id,
            'pay_id' => $request->tracking_code,
        ]);
        ReagentCode::where('reagent_code', $user->reagent_code)
            ->update([
                'checkout' => true,
            ]);
        Swal::success('موفقیت آمیز.', 'ثبت سند تسویه حساب بازاریاب با موفقیت انجام شد.');
        return back();
    }

    public function chargeAllUser(Request $request)
    {
        $users = User::whereBanned(0)->get();
        $commit = '';
        $walletHandler = new WalletHandler();
        $type = 'input';
        $reduce = false;
        $successMessage = 'شارژ کیف پول به مبلغ ' . $request->price . ' برای همه کاربران با موفقیت انجام شد.';
        if ((int)$request->price < 0) {
            $type = 'output';
            $reduce = true;
            $successMessage = 'مبلغ ' . abs($request->price) . ' از کیف پول همه کاربران کسر شد.';
        }

        try {
            $commit = DB::transaction(function () use ($request, $users, $reduce, $walletHandler, $type) {
                foreach ($users as $user) {

                    $wallet = Wallet::create([
                        'user_id' => $user->id,
                        'cost' => $request->price,
                        'wallet_type' => $type,
                    ]);
                    if ($reduce) {
                        if ($data = $walletHandler->NegativeRecordReducer($wallet, '', $user->id)) {
                            $wallet->reducedItem()->attach($data);
                        } else {
                            throw new WalletNotReduced;
                        }
                    }
                }
                return true;
            });
        } catch (WalletNotReduced $e) {
            Swal::error('موجودی ناکافی', 'موجودی کیف پول برخی کاربران ناکافی است');
        }

        if ($commit == true) {
            Swal::success('موفق', $successMessage);
        }

        return back();
    }

    public function walletBatchCharge(Request $request)
    {
        $charge_value = $request->charge_value;
        $userIds = $request->user_id;
        foreach ($userIds as $userId) {
            $wallet = new Wallet();
            $wallet->user_id = $userId;
            $wallet->cost = $charge_value;
            $wallet->wallet_type = 'input';
            $wallet->tracking_code = null;
            $wallet->save();
        }
        Swal::success('شارژ کیف پول کاربران', 'کیف پول کاربران با موفقیت شارژ شد.');
        return redirect()->back();
    }

    public function getWalletStockViaAjax(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|numeric|exists:users,id',
        ]);
        $user = User::find($request->user_id);
        return response()->json([
            'wallet_stock' => $user->wallet()->sum('cost')
        ]);
    }
}
