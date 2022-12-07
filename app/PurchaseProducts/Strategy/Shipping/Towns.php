<?php


namespace App\PurchaseProducts\Strategy\Shipping;


use App\Cart;
use App\Discount;
use App\PurchaseProducts\Decorator\ShippingPrice;

class Towns implements ShippingInterface
{
    private $discount;
    public function __construct(?Discount $discount)
    {
        $this->discount = $discount;
    }
    public function shippingCost($userId)
    {
        $carts = Cart::where('user_id' , $userId)
            ->with([
                'product'
            ])
            ->get();
        $totalPrice = 0;
        foreach($carts as $cart){
            $product = $cart->product;
            $shippingPrice = $product->shipping_price_to_other_towns;
            if ($this->discount) {
                if ($this->discount->discountable_type == "all-sending") {
                    $shippingPrice = $this->discount->applyOn($shippingPrice);
                }
                if ($this->discount->discountable_type == "store-sending" && $product->store_id == $this->discount->discountable_id) {
                    $shippingPrice = $this->discount->applyOn($shippingPrice);
                }
                if ($this->discount->discountable_type == "product-sending" && $product->id == $this->discount->discountable_id) {
                    $shippingPrice = $this->discount->applyOn($shippingPrice);
                }
            }
            $totalPrice += $shippingPrice;
        }
        return $totalPrice;
    }
}