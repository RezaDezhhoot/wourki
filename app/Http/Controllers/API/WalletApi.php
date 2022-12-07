<?php

namespace App\Http\Controllers\API;

use App\Wallet;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;

class WalletApi extends Controller
{
    public function userWallet(Request $request)
    {
        $user = auth()->guard('api')->user();
        $offset = $request->filled('offset') ? $request->offset : 0 ;
        $limit = $request->filled('limit') ? $request->limit : 1 ;
        $lists = Wallet::where('user_id', $user->id)
            ->orderBy('id' , 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
        $sumPrice = Wallet::where('wallet.user_id', $user->id)
            ->sum('cost');
        return response()->json(['lists' => $lists , 'sumPrice' => $sumPrice] , 200);
    }

    public function userCharge(Request $request)
    {
        try {
            $cost = $request->cost * 10;
            $request->session()->put('token' , $request->token);
            $request->session()->put('cost', $cost);

            $gateway = Gateway::zarinpal();
            $gateway->setCallback(route('user.charge.wallet.callback'));
            $gateway
                ->price($cost)
                ->ready();

            return $gateway->redirect();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function callbackChargeWallet(Request $request)
    {
        try {
            $gateway = Gateway::verify();
            $trackingCode = $gateway->trackingCode();

            $token = $request->session()->get('token');
            $request->headers->add([
                'Authorization' => 'Bearer ' . $token,
            ]);
            Wallet::create([
                'user_id'       => auth()->guard('api')->user()->id,
                'cost'          => $request->session()->get('cost') / 10,
                'wallet_type'   => 'input',
                'tracking_code' => $trackingCode ,
            ]);

            $request->session()->put('gateway_tracking_code', $trackingCode);
            $request->session()->put('cart_payment_date', jDate()->format('%d %B %Y'));
            return redirect()->route('user.charge.wallet.finalize');

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

    public function userChargeWalletFinalize()
    {
        return view('app.bill-payment-successful');
    }
}
