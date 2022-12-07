<?php


namespace App\PurchaseProducts\Strategy\Shipping;


interface ShippingInterface
{
    public function shippingCost($userId);
}