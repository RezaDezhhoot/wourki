<?php

namespace App\Http\Controllers\API;

use App\Bill;
use App\BillItem;
use App\Cart;
use App\Products;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Morilog\Jalali\Jalalian;

class GatewayApi extends Controller
{
    public function pay(Request $request)
    {
        if($request->has('access_token')){
            $request->headers->set('Authorization' , 'Bearer ' . $request->access_token);
            $request->headers->set('Accept' , 'application/json');
        }

        try {
            $cartPrice = \DB::table('cart')
                ->join('product' , 'product.id' , '=' , 'cart.product_id')
                ->where('cart.user_id' , '=' , auth()->guard('api')->user()->id)
                ->sum(\DB::raw('ROUND( ( cart.quantity * ( product.price - ( product.price * product.discount / 100 ) ) )  )'));

            $cartPrice = $cartPrice * 10;
            $gateway = \Gateway::zarinpal();
            $gateway->setCallback(route('cart.payment.callback.app'));
            $gateway->price($cartPrice)->ready();
            \request()->session()->put('cart_price_to_pay' , $cartPrice);
            \request()->session()->put('cart_description' , $request->description);
            \request()->session()->put('cart_address' , $request->address);
            \request()->session()->put('cart_postal_code' , $request->postal_code);
            \request()->session()->put('cart_city' , $request->city_id);
            \request()->session()->put('cart_payment_user_id' , auth()->guard('api')->user()->id);
            return $gateway->redirect();

        } catch (exception $e) {
            echo $e->getMessage();
        }
    }

    public function callback(Request $request)
    {
        try {
            $gateway = \Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $user = User::find($request->session()->get('cart_payment_user_id'));
            request()->session()->put('gateway_tracking_code' , $trackingCode);
            $bill = new Bill();
            $bill->user_id = $user->id;
            $bill->description = $request->session()->get('cart_description');
            $bill->address = $request->session()->get('cart_address');
            $bill->postal_code  = $request->session()->get('cart_postal_code');
            $bill->city_id   = $request->session()->get('cart_city');
            $bill->status  = 'bought';
            $bill->pay_type   = 'online';
            $bill->pay_referral_code   = $trackingCode;
            $bill->save();

            $cart = new Cart();
            $cartQuery = $cart->dbSelect(Cart::FIELDS)
                ->where('user_id', '=', $user->id)
                ->get();

            $cartQuery = collect($cartQuery);
            foreach($cartQuery as $index => $cart){
                $product = Products::find($cart->product_id);

                $bItem = new BillItem();
                $bItem->bill_id = $bill->id;
                $bItem->product_id = $product->id;
                $bItem->quantity = $cart->quantity;
                $bItem->price = $product->price;
                $bItem->discount = $product->discount;
                $bItem->save();

                $product->quantity -= $cart->quantity;
                $product->save();
            }
            $request->session()->put('cart_payment_date', \jdate(Carbon::now()->toDateTimeString())->format('%d %B %Y'));
            Cart::where('user_id' , $user->id)
                ->delete();
            return redirect()->route('cart.payment.successful.app');
        }catch (RetryException $e)
        {
            echo $e->getMessage();
        }
        catch (PortNotFoundException $e)
        {
            echo $e->getMessage();
        }
        catch (InvalidRequestException $e)
        {
            echo $e->getMessage();
        }
        catch (NotFoundTransactionException $e)
        {
            echo $e->getMessage();
        }
        catch (Exception $e)
        {
            echo $e->getMessage();
        }
    }
    public function cartPaymentSuccessful(Request $request){
        return view('app.cart-payment-successful');
    }
}
