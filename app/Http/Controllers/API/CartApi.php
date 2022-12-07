<?php

namespace App\Http\Controllers\API;

use App\Attribute;
use App\Cart;
use App\CartAttribute;
use App\Http\Controllers\Controller;
use App\Product_seller_attribute;
use App\Product_seller_photo;
use App\ProductSeller;
use App\Store;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CartApi extends Controller
{

    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();
        $userCarts = Cart::where('product_seller_id', $request->product_id)->where('user_id' , $user->id);
        $attributes = $request->attrs;
        $product = ProductSeller::where('id', $request->product_id)->first();
        $market_id = null;
        if($request->has('market_id')){
            $market_id = $request->market_id;
        }
        if(!$product){
            return response()->json([
                'status' => 404
            ]);
        }
        // user should not buy anything from himself
        if($user->id == $product->store->user_id){
            return response()->json([
                'status' => 402
            ]);
        }
        $attributeCollection = collect($attributes);
        $attributeFound = false;
        $userCart1 = null;
        if ($userCarts->exists()) {
            // if user selected one or more attributes for product he want to buy
            if ($request->has('attrs') && count($attributes) > 0) {
                foreach ($userCarts->get() as $userCart) {
                    $productAttribute = CartAttribute::where('cart_id', $userCart->id)->pluck('product_seller_attribute_id')->toArray();
                    // if user wants to buy a product that it's attribute is different as previously added product
                    $diff = $attributeCollection->diff($productAttribute)->toArray();
                    if (count($diff) == 0) {
                        $attributeFound = true;
                        $userCart1 = $userCart;
                        break;
                    }
                }
                // if attributes the user want to add is not in attributes he previously added
                if ($attributeFound) {
                    $userCart1->quantity = $request->quantity + $userCart1->quantity;
                    $userCart1->market_id = $market_id;
                    $userCart1->save();
                } else {
                    // if attribute the user want to add is in attributes he previously add
                    // so we should add new cart item and save new attribute
                    $cart = Cart::create([
                        'user_id' => $user->id,
                        'store_id' => $product->store_id,
                        'product_seller_id' => $product->id,
                        'quantity' => $request->quantity,
                        'market_id' => $request->market_id
                    ]);
                    foreach ($attributes as $attribute) {
                        CartAttribute::create([
                            'cart_id' => $cart->id,
                            'product_seller_attribute_id' => $attribute,
                        ]);
                    }
                }
            } else {
                // if user does not select any attribute we save cart without any attribute
                $cart = Cart::where('product_seller_id', $product->id)
                    ->where('user_id' , $user->id)
                    ->first();
                $cart->quantity += 1;
                $cart->save();
            }

            $carts = Cart::where('user_id', $user->id)->get();

            foreach($carts as $index => $cart){
                $carts[$index]->store = Store::where('id', $cart->store_id)->first()->name;
                $carts[$index]->product = ProductSeller::where('id', $cart->product_seller_id)->first()->name;
                $carts[$index]->product_quantity = ProductSeller::where('id', $cart->product_seller_id)->first()->quantity;
                $carts[$index]->discount = ProductSeller::where('id', $cart->product_seller_id)->first()->discount;
                $carts[$index]->price = ProductSeller::where('id', $cart->product_seller_id)->first()->price;
                $carts[$index]->photo = optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
                if ($cart->photo != null)
                    $carts[$index]->photo = URL::to('image/product_seller_photo') . '/' . optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
                $carts[$index]->attributess = CartAttribute::where('cart_id', $cart->id)->get();
            }
            foreach ($carts as $index => $cart) {
                foreach ($cart->attributess as $indexx => $attr) {
                    $cart->attributess[$indexx]->attribute_id = $attr->product_seller_attribute_id;
                    $cart->attributess[$indexx]->title = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->title;
                    $cart->attributess[$indexx]->extra_price = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->extra_price;
                    $cart->attributess[$indexx]->type = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->attribute_id;
                    $cart->attributess[$indexx]->type = Attribute::where('id', $cart->attributess[$indexx]->type)->first()->type;
                }
            }
            return response()->json($carts, 200);

        } else {
            $cart = new Cart();
            $cart->user_id = $user->id;
            $cart->store_id = $product->store_id;
            $cart->product_seller_id = $product->id;
            $cart->quantity = $request->quantity;
            $cart->market_id = $request->market_id;
            $cart->save();
            if ($request->has('attrs') && count($attributes) > 0) {
                foreach ($attributes as $attribute) {
                    CartAttribute::create(['cart_id' => $cart->id,
                        'product_seller_attribute_id' => $attribute,]);
                }
            }

            $carts = Cart::where('user_id', $user->id)->get();
            $carts->each(function ($cart) {
                $cart->store = Store::where('id', $cart->store_id)->first()->name;
                $cart->product = ProductSeller::where('id', $cart->product_seller_id)->first()->name;
                $cart->product_quantity = ProductSeller::where('id', $cart->product_seller_id)->first()->quantity;
                $cart->discount = ProductSeller::where('id', $cart->product_seller_id)->first()->discount;
                $cart->price = ProductSeller::where('id', $cart->product_seller_id)->first()->price;
                $cart->photo = optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
                if ($cart->photo != null)
                    $cart->photo = URL::to('image/product_seller_photo') . '/' . optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
                $cart->attributess = CartAttribute::where('cart_id', $cart->id)->get();
            });
            foreach ($carts as $index => $cart) {
                foreach ($cart->attributess as $indexx => $attr) {
                    $cart->attributess[$indexx]->attribute_id = $attr->product_seller_attribute_id;
                    $cart->attributess[$indexx]->title = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->title;
                    $cart->attributess[$indexx]->extra_price = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->extra_price;
                    $cart->attributess[$indexx]->type = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->attribute_id;
                    $cart->attributess[$indexx]->type = Attribute::where('id', $cart->attributess[$indexx]->type)->first()->type;
                }
            }
            return response()->json($carts, 200);
        }
    }

    public function index()
    {
        $user = auth()->guard('api')->user();
        $carts = Cart::where('user_id', $user->id)->get();
        $carts->each(function ($cart) {
            $product = ProductSeller::find($cart->product_seller_id);
            $cart->store = Store::where('id', $cart->store_id)->first()->name;
            $cart->product_id = $product->id;
            $cart->product = $product->name;
            $cart->product_quantity = $product->quantity;
            $cart->price = $product->price;
            $cart->discount = $product->discount;
            $cart->photo = optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
            if ($cart->photo != null)
                $cart->photo = URL::to('image/product_seller_photo') . '/' . optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
            $cart->attributess = CartAttribute::where('cart_id', $cart->id)->get();
        });
        foreach ($carts as $cart) {
            foreach ($cart->attributess as $indexx => $attr) {
                $productAttr = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first();
                $cart->attributess[$indexx]->attribute_id = $attr->product_seller_attribute_id;
                $cart->attributess[$indexx]->title = $productAttr->title;
                $cart->attributess[$indexx]->extra_price = $productAttr->extra_price;
                $cart->attributess[$indexx]->type = optional(Attribute::where('id', $cart->attributess[$indexx]->attribute_id)->first())->type;
            }
        }

        return response()->json($carts, 200);
    }
    public function indexPlus(){
        $user = auth()->guard('api')->user();
        $carts = Cart::where('user_id', $user->id)->get();
        $carts->each(function ($cart) {
            $product = ProductSeller::find($cart->product_seller_id);
            $cart->store = Store::where('id', $cart->store_id)->first()->name;
            $cart->product_id = $product->id;
            $cart->product = $product->name;
            $cart->product_quantity = $product->quantity;
            $cart->price = $product->price;
            $cart->totalPrice = ($product->price - (($product->price * $product->discount) / 100)) * $cart->quantity;
            $cart->discount = $product->discount;
            $cart->photo = optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
            if ($cart->photo != null)
                $cart->photo = URL::to('image/product_seller_photo') . '/' . optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
            $cart->attributess = CartAttribute::where('cart_id', $cart->id)->get();
            $cart->shipping_price_to_tehran = $product->shipping_price_to_tehran;
            $cart->shipping_price_to_other_towns = $product->shipping_price_to_other_towns;
        });
        $attrPrice = 0;
        foreach ($carts as  $cart) {
            foreach ($cart->attributess as $indexx => $attr) {
                $productAttr = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first();
                $cart->attributess[$indexx]->attribute_id = $attr->product_seller_attribute_id;
                $cart->attributess[$indexx]->title = $productAttr->title;
                $cart->attributess[$indexx]->extra_price = $productAttr->extra_price;
                $cart->sumAttrPrice += $attr->attribute->extra_price * $cart->quantity;
                $attrPrice += $attr->attribute->extra_price * $cart->quantity;
                $cart->attributess[$indexx]->type = optional(Attribute::where('id', $cart->attributess[$indexx]->attribute_id)->first())->type;
            }
            $cart->totalPrice += $attrPrice;
        }
        $sumPrice = $carts->sum('totalPrice');
        $totalShippingPriceToTehran = $carts->sum('shipping_price_to_tehran');
        $totalShippingPriceToOtherTowns = $carts->sum('shipping_price_to_other_towns');
        $userWallet = Wallet::where('user_id', $user->id)
        ->sum('cost');

        return response()->json(['status' => 200 , 'cart' => $carts, 'user_wallet' => $userWallet , 'tehran_shipping_price' => $totalShippingPriceToTehran , 'towns_shipping_price' => $totalShippingPriceToOtherTowns , 'total_price' => $sumPrice], 200);
    
    }
    public function decrease(Request $request)
    {
        $cart = Cart::find($request->cartId);
        if ($cart->quantity - $request->quantity <= 0) {
            $cart->attributes()->delete();
            $cart->delete();
        } else {
            $cart->quantity = $cart->quantity - $request->quantity;
            $cart->save();
        }

        $user = auth()->guard('api')->user();
        $carts = Cart::where('user_id', $user->id)->get();
        $carts->each(function ($cart) {
            $cart->store = Store::where('id', $cart->store_id)->first()->name;
            $cart->product = ProductSeller::where('id', $cart->product_seller_id)->first()->name;
            $cart->product_quantity = ProductSeller::where('id', $cart->product_seller_id)->first()->quantity;
            $cart->price = ProductSeller::where('id', $cart->product_seller_id)->first()->price;
            $cart->discount = ProductSeller::where('id', $cart->product_seller_id)->first()->discount;
            $cart->photo = optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
            if ($cart->photo != null)
                $cart->photo = URL::to('image/product_seller_photo') . '/' . optional(Product_seller_photo::where('seller_product_id', $cart->product_seller_id)->first())->file_name;
            $cart->attributess = CartAttribute::where('cart_id', $cart->id)->get();
        });
        foreach ($carts as $index => $cart) {
            foreach ($cart->attributess as $indexx => $attr) {
                $cart->attributess[$indexx]->attribute_id = $attr->product_seller_attribute_id;
                $cart->attributess[$indexx]->title = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->title;
                $cart->attributess[$indexx]->extra_price = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->extra_price;
                $cart->attributess[$indexx]->type = Product_seller_attribute::where('id', $attr->product_seller_attribute_id)->first()->attribute_id;
                $cart->attributess[$indexx]->type = Attribute::where('id', $cart->attributess[$indexx]->type)->first()->type;
            }
        }
        return response()->json($carts, 200);
    }
}