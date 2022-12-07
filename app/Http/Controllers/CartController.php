<?php

namespace App\Http\Controllers;

use App\Address;
use App\Cart;
use App\CartAttribute;
use App\Libraries\Swal;
use App\Product_seller_attribute;
use App\ProductSeller;
use App\Province;
use App\Wallet;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /*public function delete($cart)
    {
        $cart = Cart::where('product_id', $cart)->delete();
        if ($cart) {
            Swal::success('حذف موفقیت آمیز', 'حذف محصول با موفقیت انجام شد.');
            return redirect()->back();
        }
    }

    public function deleteSession($cart)
    {
        $cartArray = Session::get('tmp_cart');
        unset($cartArray[$cart]);
        \request()->session()->put('tmp_cart', $cartArray);
        Swal::success('حذف موفقیت آمیز', 'حذف محصول با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function deleteAll()
    {
        if (\request()->session()->has('tmp_cart')) {
            \request()->session()->forget('tmp_cart');
            Swal::success('حذف موفقیت آمیز', 'حذف محصول با موفقیت انجام شد.');
            return redirect()->back();
        } else {
            $user = auth()->guard('web')->user();
            $cart = Cart::where('user_id', $user->id)->delete();
            if ($cart) {
                Swal::success('حذف موفقیت آمیز', 'حذف محصول با موفقیت انجام شد.');
                return redirect()->back();
            }
        }
    }

    public function getByUserId()
    {
        $province = Province::get()->all();
        $data['provinces'] = $province;
        return view('frontend.user.cart')->with($data);
    }


    public function add($cart)
    {
        if (auth()->guard('web')->check()) {
            $user = auth()->guard('web')->user();

            $cartExist = Cart::where('product_id', $cart)
                ->where('user_id', $user->id)->first();
            if ($cartExist) {
                $cartQ = $cartExist;
                $cartQ->quantity += 1;
            } else {
                $cartQ = new Cart();
                $cartQ->quantity = 1;
            }
            $cartQ->user_id = $user->id;
            $cartQ->product_id = $cart;
            $cartQ->save();
            if ($cartQ) {
                Swal::success('ثبت موفقیت آمیز', 'محصول مورد نظر با موفقیت به سبد خرید اضافه شد.');
                return redirect()->to('user/cart');
            }
        } else {
            $cartProducts = Session::get('tmp_cart');
            if (!$cartProducts) {
                $cartProducts = [];
            }
            if (isset($cartProducts[$cart])) {
                $cartProducts[$cart] = $cartProducts[$cart] + 1;
            } else {
                $cartProducts[$cart] = 1;
            }
            Session::put('tmp_cart', $cartProducts);
//            dd($cartProducts);
//            session::save();


            swal::success('ثبت موفقیت آمیز', 'محصول مورد نظر با موفقیت به سبد خرید اضافه شد.');
            return redirect()->to('user/cart');
        }

    }

    public function addByPost(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'nullable|numeric',
        ]);
        if (auth()->guard('web')->check()) {
            $user = auth()->guard('web')->user();
            $quantity = $request->quantity;
            $productId = $request->id;

            $cartExist = Cart::where('product_id', $productId)
                ->where('user_id', $user->id)
                ->first();
            if ($cartExist) {
                $cart = $cartExist;
                $cart->quantity += $quantity;
            } else {
                $cart = new Cart();
                $cart->user_id = $user->id;
                $cart->product_id = $productId;
                $cart->quantity = $quantity;
            }
            $cart->save();
            if ($cart) {
                Swal::success('ثبت موفقیت آمیز', 'محصول مورد نظر با موفقیت به سبد خرید اضافه شد.');
                return redirect()->to('user/cart');
            }
        } else {
            $cartProducts = \request()->session()->get('tmp_cart');
            if (!$cartProducts) {
                $cartProducts = [];
            }
            if (isset($cartProducts[$request->id])) {
//                dd($cartProducts[$request->id]);
                $cartProducts[$request->id] = $cartProducts[$request->id] + $request->quantity;
            } else {
                $cartProducts[$request->id] = $request->quantity;
            }
//            dd(request()->session()->get('tmp_cart'));
            \request()->session()->put('tmp_cart', $cartProducts);
        }


        Swal::success('ثبت موفقیت آمیز', 'محصول مورد نظر با موفقیت به سبد خرید اضافه شد.');
        return redirect()->to('user/cart');
    }

    public function cartPlus(Cart $cart)
    {
        if (auth()->guard('web')->check()) {
            $cart->quantity += 1;
            $cart->save();
            return redirect()->back();
        }
    }

    public function cartPlusBySession($cart)
    {
        $cartProduct = \request()->session()->get('tmp_cart');
        if (isset($cartProduct[$cart])) {
            $cartProduct[$cart] = $cartProduct[$cart] + 1;
        }
        Session::put('tmp_cart', $cartProduct);
        return redirect()->back();
    }

    public function cartMinus(Cart $cart)
    {
        $cart->quantity -= 1;
        $cart->save();
        if ($cart->quantity == 0) {
            $cart->delete();
        }
        return redirect()->back();
    }

    public function cartMinesBySession($cart)
    {
        $cartProduct = \request()->session()->get('tmp_cart');
        if (isset($cartProduct[$cart])) {
            $cartProduct[$cart] = $cartProduct[$cart] - 1;
            if ($cartProduct[$cart] == 0) {
                unset($cartProduct[$cart]);
            }
        }
        Session::put('tmp_cart', $cartProduct);
        return redirect()->back();
    }*/


    /*-----------------------------------------------------------------------------------------------*/

    public function userCarts()
    {
        $user = auth()->guard('web')->user();
        $carts = $user->carts;
//            ->with([
//            'product' => function ($query) {
//            $query->select('name as product_name' , 'price' , 'discount' , 'store.name as store' , '')
//                ->join('store' , 'store.id' , '=' , 'product_seller.store_id')
//            }
//        ]);
        $carts->each(function ($cart) {
            $cart->product_name = $cart->product->name;
            $cart->price = $cart->product->price;
            $cart->discount = $cart->product->discount;
            $cart->store = $cart->store->name;
            $cart->totalPrice = ($cart->product->price - (($cart->product->price * $cart->product->discount) / 100)) * $cart->quantity;
            $cart->photo = optional($cart->product->photos->first())->file_name;
            $cart->attributesProduct = $cart->attributes;
            $cart->shipping_price_to_tehran = $cart->product->shipping_price_to_tehran;
            $cart->shipping_price_to_other_towns = $cart->product->shipping_price_to_other_towns;
        });
        $attrPrice = 0;
        foreach ($carts as $index => $cart) {
            foreach ($carts[$index]->attributesProduct as $indexx => $attribute) {
                $carts[$index]->sumAttrPrice += $attribute->attribute->extra_price * $cart->quantity;
                $attrPrice += $attribute->attribute->extra_price * $cart->quantity;
                $carts[$index]->attributesProduct[$indexx]->name = $attribute->attribute->title;
                $carts[$index]->attributesProduct[$indexx]->extra_price = $attribute->attribute->extra_price;
                $carts[$index]->attributesProduct[$indexx]->attribute = $attribute->attribute->attribute->type;
            }
            $carts[$index]->totalPrice += $attrPrice;
        }
        $sumPrice = $carts->sum('totalPrice');
        $totalShippingPriceToTehran = $carts->sum('shipping_price_to_tehran');
        $totalShippingPriceToOtherTowns = $carts->sum('shipping_price_to_other_towns');
        $userWallet = Wallet::where('user_id', $user->id)
            ->sum('cost');

        $addresses = Address::where('user_id', auth()->guard('web')->user()->id)
            ->where('status', 'active')
            ->get();
        $provinces = Province::all();
        return view('frontend.cart.index', compact('totalShippingPriceToTehran', 'totalShippingPriceToOtherTowns', 'carts', 'addresses', 'provinces', 'sumPrice', 'userWallet'));
    }

    public function calcUserCartPrice()
    {
        $carts = auth()->guard('web')->user()->carts;
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

    public function increaseQuantity(Cart $cart)
    {
        $productQuantity = $cart->product->quantity;
        if ($cart->quantity < $productQuantity) {
//            $cartAttrExists = CartAttribute::where('cart_id' , $cart->id)->exists();
//            if ($cartAttrExists)
            $cart->quantity = $cart->quantity + 1;
            $cart->save();
            return back();
        } else {
            Swal::error('خطا!', 'با عرض پوزش موجودی انبار بیشتر از تعداد درخواستی است.');
            return back();
        }
    }

    public function decreaseQuantity(Cart $cart)
    {
        if ($cart->quantity == 1) {
            $cart->attributes()->delete();
            $cart->delete();
            return back();
        } else {
            $cart->quantity = $cart->quantity -= 1;
            $cart->save();
            return back();
        }
    }

    public function delete(Cart $cart)
    {
        $cart->delete();
        return back();
    }

    public function store(Request $request)
    {
        if (auth()->check()) {
            $user = auth()->guard('web')->user();
            //checking for market
            $market_id = null;
            if(session()->has('validated_market_code')){
                if(session()->get('validated_product_id') == $request->product_id){
                    $market_id = session()->get('validated_market_code');
                }
            }
            $userCarts = Cart::where('product_seller_id', $request->product_id)
                ->where('user_id', $user->id);
            $attributes = [];
            for ($i = 0; $i <= $request->$i; $i++) {
                $attributes[] = $request->$i;
            }
            foreach ($attributes as $attribute) {
                if ($attribute == null)
                    array_splice($attributes, $attribute);
            }
            $product = ProductSeller::where('id', $request->product_id)->first();
            if ($user->id == $product->store->user_id) {
                Swal::error('خطا', 'شما قادر به خرید از فروشگاه خودتان نیستید.');
                return redirect()->back();
            }
            $attributeCollection = collect($attributes);
            $attributeFound = false;

            if ($userCarts->exists()) {
                if (count($attributes) >= 1) {
                    foreach ($userCarts->get() as $userCart) {
                        $productAttribute = CartAttribute::where('cart_id', $userCart->id)->pluck('product_seller_attribute_id')->toArray();
//                        dd($productAttribute);
                        $diff = $attributeCollection->diff($productAttribute)->toArray();
                        if (count($diff) == 0) {
                            $attributeFound = true;
                            break;
                        }
                    }
                    if ($attributeFound) {
                        $userCart->quantity += 1;
                        $userCart->market_id = $market_id;
                        $userCart->save();
                    } else {
                        $cart = Cart::create([
                            'user_id' => $user->id,
                            'store_id' => $product->store_id,
                            'product_seller_id' => $product->id,
                            'market_id' => $market_id,
                            'quantity' => 1,
                        ]);
                        foreach ($attributes as $attribute) {
                            CartAttribute::create([
                                'cart_id' => $cart->id,
                                'product_seller_attribute_id' => $attribute,
                            ]);
                        }
                    }
                } else {
                    $cart = Cart::where('product_seller_id', $product->id)->first();
                    $cart->quantity += 1;
                    $cart->market_id = $market_id;
                    $cart->save();
                }

                Swal::success('تبریک', 'محصول مورد نظر با موفقیت به سبد خرید اضافه گردید.');
                return redirect()->route('user.carts');
            } else {
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'store_id' => $product->store_id,
                    'product_seller_id' => $product->id,
                    'market_id' => $market_id,
                    'quantity' => 1,
                ]);
                if (count($attributes) >= 1) {
                    foreach ($attributes as $attribute) {
                        CartAttribute::create(['cart_id' => $cart->id,
                            'product_seller_attribute_id' => $attribute,]);
                    }
                }
                Swal::success('تبریک', 'محصول مورد نظر با موفقیت به سبد خرید اضافه گردید.');
                return redirect()->route('user.carts');
            }
        } else {
            Swal::error('خطا!', 'جهت افزودن محل به سبد خرید ابتدا ثبت نام کنید.');
            return back();
        }
    }

}
