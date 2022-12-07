<?php


namespace App\PurchaseProducts\Decorator;


use App\Cart;
use App\Discount;
use App\PurchaseProducts\Decorator\Exceptions\CartIsEmptyException;
use App\PurchaseProducts\Strategy\Shipping\Shipping;
use App\UsedDiscount;

// This class calculates the price of products in the cart using the decorator design pattern
class CartDecorator implements CartDecoratorInterface
{
    protected $userId;
    protected $shipping;
    public $usedDiscounts;
    public function __construct($userId , Shipping $shipping , ?Discount $discount)
    {
        $this->userId = $userId;
        $this->shipping = $shipping;
        $this->discount = $discount;
        $this->usedDiscounts = [];
    }

    function totalPrice()
    {
        // get cart items with it's product and their attributes
        $carts = Cart::where('user_id', $this->userId)
            ->with([
                'product' ,
                'attributes',
                'attributes.attribute',
            ])
            ->get();
        // if user's cart is empty throw an error
        if(count($carts) == 0 ){
            throw new CartIsEmptyException();
        }
        $totalPrice = 0;
        foreach ($carts as $cart) {
            $product = $cart->product;
            // calculate the price of each product
            if(!$this->discount){
            $productPrice = $cart->quantity * (
                    $product->price - ($product->discount / 100 * $product->price)
                );
            }
            else{
                $productPrice = $product->price;
                if($product->store->store_type == 'product'){
                    $discount = Discount::getDiscountFor($this->discount->code,'product',$product->id);
                }
                else{
                    $discount = Discount::getDiscountFor($this->discount->code, 'service', $product->id);
                }
                $innerDiscount = ($product->discount / 100 * $product->price);
                $productPrice -= $innerDiscount;
                if ($discount){
                    $usedDiscount = new UsedDiscount();
                    $usedDiscount->user_id = $this->userId;
                    $usedDiscount->discount_id = $discount->id;
                    $usedDiscount->price = $productPrice;
                    $productPrice = $discount->applyOn($productPrice);
                    $usedDiscount->price_with_discount = $productPrice;
                    $usedDiscount->save();
                    $this->usedDiscounts[] = $usedDiscount->id;
                }
                $productPrice *= $cart->quantity;
            }
            $totalPrice += $productPrice;
            // calculate the price of product attribute
            $cartAttributes = $cart->attributes;

            foreach($cartAttributes as $attribute){
                $productAttribute = $attribute->attribute;
                $totalPrice += $productAttribute->extra_price;
            }
        }
        //return total price of cart
        return $totalPrice;
    }
}