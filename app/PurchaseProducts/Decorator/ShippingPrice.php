<?php


namespace App\PurchaseProducts\Decorator;


use App\PurchaseProducts\Strategy\Shipping\Shipping;
// This class calculate shipping price using decorator design pattern
class ShippingPrice extends AbstractCartDecorator
{
    public function __construct($userId, CartDecoratorInterface $cartDecorator, Shipping $shipping)
    {
        parent::__construct($userId, $cartDecorator, $shipping);
    }

    function totalPrice()
    {
        // get total price of product in cart with their attributes
        $totalPrice = $this->shipping->shippingCost();
        // add shipping price to product prices and return it
        return $this->cartDecoratorInterface->totalPrice() + $totalPrice;
    }
}