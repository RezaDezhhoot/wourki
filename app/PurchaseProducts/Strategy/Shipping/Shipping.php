<?php


namespace App\PurchaseProducts\Strategy\Shipping;



class Shipping
{
    protected $shippingInterface;
    protected $userId;
    public function __construct(ShippingInterface $shipping , $userId)
    {
        $this->shippingInterface = $shipping;
        $this->userId = $userId;
    }

    public function shippingCost(){
        return $this->shippingInterface->shippingCost($this->userId);
    }
}