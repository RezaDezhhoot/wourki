<?php


namespace App\PurchaseProducts\ChainOfResponsibility;


use App\Cart;
use App\ProductSeller;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\ProductStockIsNotEnough;
use App\PurchaseProducts\Exceptions\UserNotPassedException;
// this class check if product stock is enough of not
// if it's not enough throw an error
class CheckProductStock extends PurchaseProductBaseHandler
{

    public function handle($request = null)
    {
        if(!isset($request['user_id'])){
            throw new UserNotPassedException('User id not passed');
        }
        $userId = $request['user_id'];
        // get the products in the shopping cart to check their inventory
        $carts = Cart::where('user_id' , $userId)->get();
        $productStockNotEnoughError = false;
        $errors = [];
        foreach($carts as $cart){
            $product = ProductSeller::find($cart->product_seller_id);
            $cartQuantity = $cart->quantity;
            $productQuantity = $product->quantity;
            if($cartQuantity > $productQuantity){
                $productStockNotEnoughError = true;
                $error = [
                    'message' => 'موجودی محصول ' . $product->name . ' از مقدار درخواستی شما بیشتر است.',
                    'id' => $cart->id,
                    'quantity' => $productQuantity
                ];
                $errors[] = $error;
            }
        }
        // if the product inventory was less than the user requested inventory throw an error
        if($productStockNotEnoughError){
            throw new ProductStockIsNotEnough("" , 0 , null , $errors);
        }
        parent::handle($request);
    }
}