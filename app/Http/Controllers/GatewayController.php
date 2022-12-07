<?php

namespace App\Http\Controllers;

use App\Bill;
use App\BillItem;
use App\Cart;
use App\Libraries\Swal;
use App\Products;
use Exception;
use Illuminate\Http\Request;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;

class GatewayController extends Controller
{
    public function pay(Request $request)
    {
        $this->validate($request, [
            'province' => 'required|numeric|exists:province,id',
            'city' => 'required|numeric|exists:city,id',
            'postal_code' => 'required|numeric',
            'address' => 'required|string',
        ]);
        try {
            $cartInfo = \DB::table('cart')
                ->join('product' , 'product.id' , '=' , 'cart.product_id')
                ->select('product.name' , 'product.quantity as product_quantity' , 'cart.quantity as cart_quantity')
                ->where('cart.user_id' , '=' ,auth()->guard('web')->user()->id)
                ->get();

            foreach($cartInfo as $cInfo){
                if($cInfo->cart_quantity > $cInfo->product_quantity){
                    Swal::error('خطا', sprintf('تعداد درخواستی محصول %s از موجودی آن بیشتر است.', $cInfo->name));
                    return redirect()->back();
                }
            }
            $cartPrice = \DB::table('cart')
                ->join('product' , 'product.id' , '=' , 'cart.product_id')
                ->where('cart.user_id' , '=' , auth()->guard('web')->user()->id)
                ->sum(\DB::raw('ROUND( ( cart.quantity * ( product.price - ( product.price * product.discount / 100 ) ) )  )'));

            $cartPrice = $cartPrice * 10;
            $gateway = \Gateway::zarinpal();
            $gateway->setCallback(url('callback/from/bank'));
            $gateway->price($cartPrice)->ready();
            \request()->session()->put('cart_price_to_pay' , $cartPrice);
            \request()->session()->put('cart_description' , \request()->input('description'));
            \request()->session()->put('cart_address' , \request()->input('address'));
            \request()->session()->put('cart_postal_code' , \request()->input('postal_code'));
            \request()->session()->put('cart_city' , \request()->input('city'));
            return $gateway->redirect();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function callback(Request $request)
    {
        try {
            $gateway = \Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            request()->session()->put('gateway_tracking_code' , $trackingCode);
            $bill = new Bill();
            $bill->user_id = auth()->guard('web')->user()->id;
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
                ->where('user_id', '=', auth()->guard('web')->user()->id)
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

            Cart::where('user_id' , auth()->guard('web')->user()->id)
                ->delete();
            Swal::success('پرداخت موفقیت آمیز', 'پرداخت شما با موفقیت انجام شد. با تشکر از خرید شما');
            return redirect()->route('showBillItemsUser' , $bill->id);
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

    public function detectPaymentType(Request $request){
        $this->validate($request, [
            'province' => 'required|numeric|exists:province,id',
            'city' => 'required|numeric|exists:city,id',
            'postal_code' => 'required|numeric',
            'address' => 'required|string',
        ]);
        $cartInfo = \DB::table('cart')
            ->join('product' , 'product.id' , '=' , 'cart.product_id')
            ->select('product.name' , 'product.quantity as product_quantity' , 'cart.quantity as cart_quantity')
            ->where('cart.user_id' , '=' ,auth()->guard('web')->user()->id)
            ->get();

        foreach($cartInfo as $cInfo){
            if($cInfo->cart_quantity > $cInfo->product_quantity){
                Swal::error('خطا', sprintf('تعداد درخواستی محصول %s از موجودی آن بیشتر است.', $cInfo->name));
                return redirect()->back();
            }
        }
        $cartPrice = \DB::table('cart')
            ->join('product' , 'product.id' , '=' , 'cart.product_id')
            ->where('cart.user_id' , '=' , auth()->guard('web')->user()->id)
            ->sum(\DB::raw('ROUND( ( cart.quantity * ( product.price - ( product.price * product.discount / 100 ) ) )  )'));
        if($request->pay_type == 'online')
        {
            try {
                $cartPrice = $cartPrice * 10;
                $gateway = \Gateway::zarinpal();
                $gateway->setCallback(url('callback/from/bank'));
                $gateway->price($cartPrice)->ready();
                \request()->session()->put('cart_price_to_pay' , $cartPrice);
                \request()->session()->put('cart_description' , \request()->input('description'));
                \request()->session()->put('cart_address' , \request()->input('address'));
                \request()->session()->put('cart_postal_code' , \request()->input('postal_code'));
                \request()->session()->put('cart_city' , \request()->input('city'));
                return $gateway->redirect();

            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }else{
            $bill = new Bill();
            $bill->user_id = auth()->guard('web')->user()->id;
            $bill->description = $request->description;
            $bill->address = $request->address;
            $bill->postal_code  = $request->postal_code;
            $bill->city_id   = $request->city;
            $bill->status  = 'bought';
            $bill->pay_type   = 'venal';
            $bill->pay_referral_code   = null;
            $bill->save();



            $cart = new Cart();
            $cartQuery = $cart->dbSelect(Cart::FIELDS)
                ->where('user_id', '=', auth()->guard('web')->user()->id)
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

            Cart::where('user_id' , auth()->guard('web')->user()->id)
                ->delete();

            Swal::success('ثبت سفارش ', 'سفارش شما با موفقیت ثبت شد. با تشکر از خرید شما');
            return redirect()->route('showBillItemsUser' , $bill->id);
        }

    }
}
