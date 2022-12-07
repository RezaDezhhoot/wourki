<?php

namespace App\Http\Controllers\API;

use App\Address;
use App\Bill;
use App\BillItem;
use App\Cart;
use App\Discount;
use App\Http\Controllers\Controller;
use App\Process\PrNotification;
use App\Product_seller_attribute;
use App\Products;
use App\ProductSeller;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\ProductStockIsNotEnough;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\UserIsBannedException;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\WalletStockIsNotEnough;
use App\PurchaseProducts\Decorator\CartDecorator;
use App\PurchaseProducts\Decorator\Exceptions\CartIsEmptyException;
use App\PurchaseProducts\Decorator\ShippingPrice;
use App\PurchaseProducts\Documents\DocumentHandler;
use App\PurchaseProducts\Exceptions\UserNotPassedException;
use App\PurchaseProducts\Facade\Api\SaveBillFacade as SaveBillFacadeApi;
use App\PurchaseProducts\Facade\Bill\BillTotalPriceCalculatorFacade;
use App\PurchaseProducts\Facade\PaymentType\PaymentType;
use App\PurchaseProducts\SaveBill\BillHandler;
use App\PurchaseProducts\Strategy\Shipping\Shipping;
use App\PurchaseProducts\Strategy\Shipping\Tehran;
use App\PurchaseProducts\Strategy\Shipping\Towns;
use App\Store;
use App\User;
use App\Wallet;
use Exception;
use Illuminate\Http\Request;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;
use Log;
use Throwable;

class BillApi extends Controller
{
    public function save(Request $request)
    {
        $user = auth()->guard('api')->user();
        $cart = new Cart();
        $cartQuery = $cart->dbSelect(Cart::FIELDS)
            ->where('user_id', '=', $user->id)
            ->get();
        if (count($cartQuery) == 0) {
            return response()->json(['error' => 'not founded'], 404);
        }
        $cartQuery = collect($cartQuery);
        foreach ($cartQuery as $item) {
            $product = Products::find($item->product_id);
            if ($product->quantity && $item->quantity > $product->quantity) {
                return response()->json(['error' => 'تعداد سفارش ' . $product->name . ' از حد مجاز بیشتر می باشد'], 406);
            }
        }

        $bill = new Bill();
        $bill->user_id = $user->id;
        $bill->description = $request->description;
        $bill->address = $request->address;
        $bill->postal_code = $request->postal_code;
        $bill->status = 'bought';
        $bill->pay_type = $request->pay_type;
        $bill->city_id = $request->city_id;
        $bill->save();

        foreach ($cartQuery as $index => $cart) {
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

        Cart::where('user_id', '=', $user->id)->delete();

        return response()->json(['status' => 'success'], 200);
    }

    public function buyBill(Request $request)
    {
        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : 1;
        $user = auth()->guard('api')->user();

        $buyBills = Bill::join('store', 'store.id', '=', 'bill.store_id')
            ->join('users', 'users.id', '=', 'bill.user_id')
            ->join('address', 'address.id', '=', 'store.address_id')
            ->join('city', 'city.id', 'address.city_id')
            ->join('province', 'province.id', 'city.province_id')
            ->where('bill.user_id', $user->id)
            ->with(['billItems', 'billItems.attributes' , 'billItems.product' , 'billItems.product.photos'])
            ->with(['store', 'store.photo', 'store.address' => function ($query) {
                $query->join('city', 'city.id', '=', 'address.city_id')
                    ->join('province', 'province.id', '=', 'city.province_id')
                    ->select('address.*', 'city.name as city_name', 'province.name as province_name');
            }]);

        if ($request->filled('store_id'))
            $buyBills->where('bill.store_id', $request->store_id);
        if ($request->filled('from_date') && $request->filled('to_date'))
            $buyBills->where('bill.created_at', '>=', $request->from_date)
                ->where('created_at', '<=', $request->to_date);
        if ($request->filled('status'))
            $buyBills->where('bill.status', $request->status);

        $buyBills = $buyBills
            ->offset($offset)
            ->limit($limit)
            ->orderBy('bill.id', 'desc')
            ->select('bill.*', 'address.address as store_address', 'address.latitude as store_latitude', 'address.longitude as store_longitude',
                'store.phone_number as store_phone_number', 'users.mobile as user_mobile', 'province.name as store_province_name', 'city.name as store_city_name')
            ->get();

        //        foreach ($buyBills as $index => $buyBill) {
        //            $address = $buyBill->store->address;
        //            $buyBills[$index]->address = $address;
        //            unset($buyBill->store->address);
        //        }
        foreach ($buyBills as $buyBill) {
            $totalBillPrice = 0;
            $totalShippingPrice = 0;
            foreach ($buyBill->billItems as $billItem) {
                $billItem->product->photo_url = url()->to('image/product_seller_photo/350') . '/' . $billItem->product->photos[0]->file_name;
                unset($billItem->product->photos);
                $billItem->totalPrice = $billItem->price + $billItem->commission_price * $billItem->quantity - (($billItem->price * $billItem->quantity * $billItem->discount) / 100);
                $billItem->totalPrice += $billItem->shipping_price;
                $totalBillPrice += $billItem->totalPrice;
                $totalShippingPrice += $billItem->shipping_price;
            }
            $buyBill->totalPrice = $totalBillPrice;
            $buyBill->totalShippingPrice = $totalShippingPrice;
        }
        return response()->json($buyBills, 200);
    }

    public function sellBill(Request $request)
    {
        $user = auth()->guard('api')->user();
        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : 1;
        $userStoreExists = Store::where('user_id', $user->id)->exists();

        if ($userStoreExists) {
            $store_ids = Store::where('user_id', $user->id);
            if($request->has('store_type')){
                $store_ids->where('store_type' , $request->store_type);
            }
            $store_ids = $store_ids->get()->pluck('id');
            $sellBills = Bill::join('store', 'store.id', '=', 'bill.store_id')
                ->join('users', 'users.id', '=', 'bill.user_id')
                ->join('address', 'address.id', '=', 'store.address_id')
                ->join('city', 'city.id', 'address.city_id')
                ->join('province', 'province.id', 'city.province_id')
                ->whereIn('store_id', $store_ids);

            if ($request->filled('store_id'))
                $sellBills->where('store.id', $request->store_id);
            if ($request->filled('from_date') && $request->filled('to_date'))
                $sellBills->where('bill.created_at', '>=', $request->from_date)
                    ->where('bill.created_at', '<=', $request->to_date);
            if ($request->filled('status'))
                $sellBills->where('bill.status', $request->status);

            $sellBills = $sellBills
                ->with(['user' => function ($query) {
                    $query->select('id', 'first_name', 'last_name', 'email', 'created_at', 'updated_at', 'mobile');
                }])
                ->with(['billItems', 'billItems.attributes' , 'billItems.product' , 'billItems.product.photos'])
                ->offset($offset)
                ->limit($limit)
                ->orderBy('bill.id', 'desc')
                ->select('bill.*', 'address.address as store_address', 'address.latitude as store_latitude', 'address.longitude as store_longitude',
                    'store.phone_number as store_phone_number', 'users.mobile as user_mobile', 'province.name as store_province_name', 'city.name as store_city_name')
                ->get();
                foreach($sellBills as $sellBill){
                    $totalBillPrice = 0;
                    $totalShippingPrice = 0;
                    foreach($sellBill->billItems as $billItem){
                        $billItem->product->photo_url = url()->to('image/product_seller_photo/350'). '/' . $billItem->product->photos[0]->file_name;
                        unset($billItem->product->photos);
                    $billItem->totalPrice = $billItem->price + $billItem->commission_price * $billItem->quantity - (($billItem->price * $billItem->quantity * $billItem->discount) / 100);
                    $billItem->totalPrice += $billItem->shipping_price;
                    $totalBillPrice += $billItem->totalPrice;
                    $totalShippingPrice += $billItem->shipping_price;
                    }
                    $sellBill->totalPrice = $totalBillPrice;
                    $sellBill->totalShippingPrice = $totalShippingPrice;
                    
                }
            return response()->json($sellBills, 200);
        } else
            return response()->json([ "status" => 200], 200);

    }

    public function billSellerStore()
    {
        $user = auth()->guard('api')->user();
        $userBillInfo = Bill::join('store', 'store.id', '=', 'bill.store_id')
            ->join('users', 'users.id', 'bill.user_id')
            ->where('bill.user_id', $user->id)
            ->select('store.*')
            ->distinct('store.*')
            ->get();
        return response()->json($userBillInfo, 200);
    }

    public function changeStatus(Request $request)
    {
        Log::info('this method ran');
        $bill = Bill::where('id', $request->bill_id)->first();
        $userStore = Store::find($bill->store_id);
        $userStore = User::where('id', $userStore->user_id)->first();
        Log::info(json_encode($bill));
        if ($request->status == 'rejected') {
            // the buyer can reject the order if if has not been delivered
            // else where we should throw an error
            if ($bill->status != 'delivered') {
            if($bill->status == 'rejected'){
                return response()->json(['status' => 400], 400);
            }
                $bill->status = 'rejected';
                $bill->save();
                // if the buyer reject his invoice and pay online, the invoice price must be added to his wallet
                if (in_array($bill->pay_type, ['online', 'wallet'])) {
                    $totalPriceOfBillFacade = new BillTotalPriceCalculatorFacade($bill);
                    $totalPriceOfBill = $totalPriceOfBillFacade->getTotalPrice();
                    Log::info('total price is :');
                    Log::info(json_encode($totalPriceOfBill));
                    Wallet::create([
                        'user_id' => $bill->user_id,
                        'wallet_type' => 'reject_order',
                        'cost' => $totalPriceOfBill,
                    ]);
                }
                try{
                $notification = new PrNotification();
                $notification
                    ->setTitle('وورکی')
                    ->setBody('فروشنده گرامی فاکتور به شماره ' . $bill->id . ' توسط خریدار لغو گردید.')
                    ->addData('type', 'input')
                    ->addData('id', $bill->id)
                    ->addData('picture', null)
                    ->setUser($userStore)
                    ->send();
                }
                catch(Throwable $e){
                    //doing nothing
                }
                return response()->json(['status' => 200], 200);
            } else {
                return response()->json(['status' => 400], 400);
            }
            // if the buyer has delivered his bill, he should not naturally be able to refuse it
        } elseif ($request->status == 'delivered') {
            if ($bill->status == 'approved') {

                $result = new DocumentHandler([$bill]);
                $result->submitBillDocument();
                try{
                $notification = new PrNotification();
                $notification
                    ->setTitle('وورکی')
                    ->setBody('فروشنده گرامی فاکتور به شماره ' . $bill->id . ' توسط خریدار تحویل گردید.')
                    ->addData('type', 'input')
                    ->addData('id', $bill->id)
                    ->addData('picture', null)
                    ->setUser($userStore)
                    ->send();
                }
                catch(Throwable $e){
                    //doing nothing
                }
                return response()->json(['status' => 200], 200);
            } else {
                return response()->json(['status' => 400], 400);
            }
        }

    }

    public function changeStatusWithAdmin(Request $request)
    {
        $bill = Bill::where('id', $request->bill_id)->first();
        $userStore = User::where('id', $bill->user_id)->first();
        if ($request->status == 'rejected') {
            if ($bill->pay_type == 'wallet') {
                $billTotalPrice = $bill->bill_price;
                Wallet::create([
                    'user_id' => $bill->user_id,
                    'cost' => $billTotalPrice,
                    'wallet_type' => 'reject_order',
                ]);
            }
            if ($bill->status != 'delivered') {
                $bill->status = 'rejected';
                $bill->save();
                // $notification = new PrNotification();
                // $notification
                //     ->setTitle('وورکی')
                //     ->setBody('کاربر گرامی فاکتور به شماره ' . $bill->id . ' توسط فروشنده لغو گردید.')
                //     ->addData('type', 'output')
                //     ->addData('id', $bill->id)
                //     ->addData('picture', null)
                //     ->setUser($userStore)
                //     ->send();
                return response()->json(['status' => 200], 200);
            } else
                return response()->json(['status' => 400], 400);
        } elseif ($request->status == 'approved') {
            if ($bill->status == 'pending') {
                $bill->status = 'approved';
                $bill->save();
                // $notification = new PrNotification();
                // $notification
                //     ->setTitle('وورکی')
                //     ->setBody('کاربر گرامی فاکتور به شماره ' . $bill->id . ' توسط فروشنده تایید گردید.')
                //     ->addData('type', 'output')
                //     ->addData('id', $bill->id)
                //     ->addData('picture', null)
                //     ->setUser($userStore)
                //     ->send();
                return response()->json(['status' => 200], 200);
            } else
                return response()->json(['status' => 400], 400);
        }
    }

    public function store(Request $request)
    {

        $user = auth()->guard('api')->user();
        try {
            $paymentType = $request->pay_type == 'wallet' ?
                new PaymentType(new \App\PurchaseProducts\Facade\PaymentType\Wallet())
                : new PaymentType(new \App\PurchaseProducts\Facade\PaymentType\Online());
            $discount = null;
            if($request->filled('discount')){
            $discount = Discount::where('id', $request->discount)
                ->whereNotIn('discountable_type', ['all-ads', 'all-plans', 'ad', 'plan', 'upgrade', 'all-upgrade', 'all-sending', 'store-sending', 'product-sending'])
                ->first();
            if ($request->discount_code && $request->discount_code != '' && !$discount) {
                return response()->json(['error' => ['کد تخفیف وارد شده معتبر نیست']], 200);
            }
            }
            $savedBill = new SaveBillFacadeApi($user->id, $request->address_id, $paymentType , $discount);
            $savedBill->save();
            return response()->json(['status' => 200], 200);
        } catch (UserIsBannedException $bannedException) {
            return response()->json([
                'status' => 402,
                'message' => 'user_is_banned',
                'entire' => []
            ]);
        } catch (WalletStockIsNotEnough $exception) {
            return response()->json(['status' => 401], 401);
        } catch (ProductStockIsNotEnough $exception) {
            return response()->json([
                'errors' => $exception->getAdditionalData(),
                'status' => 400
            ]);
        } catch (CartIsEmptyException $exception) {
            return response()->json(['status' => 403, 'error' => 'cart_is_empty']);
        } catch (UserNotPassedException $exception) {
            return response()->json(['unknown_error']);
        }
    }

    public function buyBillPaymentGatewayInit(Request $request)
    {
        try {
            $request->session()->put('token', $request->token);
            $request->session()->put('address', $request->address);
            
            $request->headers->add([
                'Authorization' => 'Bearer ' . $request->token
            ]);
            $discount = null;
            if ($request->filled('discount')) {
            $discount = Discount::where('id', $request->discount)
                ->whereNotIn('discountable_type', ['all-ads', 'all-plans', 'ad', 'plan', 'upgrade', 'all-upgrade', 'all-sending', 'store-sending', 'product-sending'])
                ->first();
            if ($request->discount_code && $request->discount_code != '' && !$discount) {
                return response()->json(['error' => ['کد تخفیف وارد شده معتبر نیست']] , 200);
            }
            $request->session()->put('discount', $request->discount);
            }
            $address = Address::join('city', 'city.id', 'address.city_id')
                ->join('province', 'province.id', 'city.province_id')
                ->where('address.id', $request->address)
                ->select('city.id as city_id', 'city.name as city_name', 'province.name as province_name', 'address.id as address_id', 'address.latitude', 'address.longitude', 'address.address')
                ->first();
            $user = auth()->guard('api')->user();
            $shippingPlace = $address->city_id == 118 ? new Tehran($discount) : new Towns($discount);
            $shipping = new Shipping($shippingPlace, $user->id);
            $cartDecorator = new CartDecorator($user->id, $shipping , $discount);
            $shippingDecorator = new ShippingPrice($user->id, $cartDecorator, $shipping);
            $sumPrice = $shippingDecorator->totalPrice() * 10;
            $gateway = Gateway::zarinpal();
            $gateway->setCallback(route('buy_bill.payment_gateway_callback'));
            $gateway
                ->price($sumPrice)
                ->ready(); 
            $request->session()->put('price', $sumPrice);
            return $gateway->redirect();

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function buyBillPaymentGatewayCallback(Request $request)
    {
        try {
            $discount = $request->session()->has('discount') ? $request->session()->get('discount') : null;
            $gateway = Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $refId = $gateway->refId();

            $token = request()->session()->get('token');
            \request()->headers->add([
                'Authorization' => 'Bearer ' . $token,
            ]);
            $address = Address::join('city', 'city.id', 'address.city_id')
                ->join('province', 'province.id', 'city.province_id')
                ->where('address.id', request()->session()->get('address'))
                ->select('city.id as city_id', 'city.name as city_name', 'province.name as province_name', 'address.id as address_id', 'address.latitude', 'address.longitude', 'address.address')
                ->first();
            $user = auth()->guard('api')->user();

            $billHandler = new BillHandler($user, $address, new \App\PurchaseProducts\SaveBill\Payments\Wallet() , $discount);
            $billHandler->save();
            /*  foreach ($userCartsDistinct as $item) {
                  $address = Address::join('city', 'city.id', 'address.city_id')
                      ->join('province', 'province.id', 'city.province_id')
                      ->where('address.id', $request->session()->get('address'))
                      ->select('city.name as city_name', 'province.name as province_name', 'address.id as address_id', 'address.latitude', 'address.longitude', 'address.address')
                      ->first();
                  $userCarts = auth()->guard('api')->user()->carts->where('store_id', $item);
                  $bill = Bill::create([
                      'store_id' => $item,
                      'user_id' => auth()->guard('api')->user()->id,
                      'address_id' => $address->address_id,
                      'address' => ' استان ' . $address->province_name . ' شهر ' . $address->city_name . ' ' . $address->address,
                      'customer_lat' => $address->latitude,
                      'customer_lng' => $address->longitude,
                      'pay_type' => 'online',
                      'pay_id' => $refId,
                      'refid' => $refId,
                      'tracking_code' => $trackingCode,
                  ]);
                  foreach ($userCarts as $cart) {
                      $product = ProductSeller::find($cart->product_seller_id);
                      $product->quantity -= 1;
                      $product->save();
                      $billItem = BillItem::create([
                          'bill_id' => $bill->id,
                          'product_id' => $cart->product_seller_id,
                          'product_name' => $cart->product->name,
                          'price' => $cart->product->price,
                          'discount' => $cart->product->discount,
                          'quantity' => $cart->quantity,
                      ]);
                      $userCartAttrs = $cart->attributes;
                      foreach ($userCartAttrs as $userCartAttr) {
                          BillItemAttribute::create([
                              'bill_item_id' => $billItem->id,
                              'product_attribute_id' => $userCartAttr->product_seller_attribute_id,
                              'extra_price' => $userCartAttr->attribute->extra_price,
                              'type' => $userCartAttr->attribute->attribute->type,
                              'title' => $userCartAttr->attribute->title,
                          ]);
                          $userCartAttr->delete();
                      }
                      $cart->delete();
                  }
              }*/

            $request->session()->put('gateway_tracking_code', $trackingCode);
            $request->session()->put('cart_payment_date', \jdate()->format('%d %B %Y'));
            return redirect()->route('buy_bill.payment_gateway_finalize');

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

    public function buyBillPaymentGatewayFinalize()
    {
        return view('app.bill-payment-successful');
    }

    public function calcUserCartPrice()
    {
        $token = \request()->session()->get('token');
        \request()->headers->add([
            'Authorization' => 'Bearer ' . $token,
        ]);
        $carts = auth()->guard('api')->user()->carts;
        $total = 0;
        foreach ($carts as $cart) {
            $userCartAttrs = $cart->attributes;
            $attrPrice = 0;
            if ($userCartAttrs) {
                foreach ($userCartAttrs as $cartAttr) {
                    $extraPrice = Product_seller_attribute::find($cartAttr->product_seller_attribute_id)->extra_price;
                    $attrPrice += $extraPrice;
                }
            }
            $price = $this->calcProductDiscount($cart->product->id) * $cart->quantity + $attrPrice;
            $total = $price + $total;
        }
        return $total;
    }

    public function calcProductDiscount($product)
    {
        $product = ProductSeller::find($product);
        $price = $product->price - (($product->price * $product->discount) / 100);
        return $price;
    }
}
