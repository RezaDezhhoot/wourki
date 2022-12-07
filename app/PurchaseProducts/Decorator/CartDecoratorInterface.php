<?php


namespace App\PurchaseProducts\Decorator;


use App\PurchaseProducts\Strategy\Shipping;

interface CartDecoratorInterface
{
    public function totalPrice();
}