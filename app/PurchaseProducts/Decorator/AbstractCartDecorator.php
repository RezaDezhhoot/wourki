<?php


namespace App\PurchaseProducts\Decorator;



use App\PurchaseProducts\Strategy\Shipping\Shipping;

abstract class AbstractCartDecorator implements CartDecoratorInterface
{
    protected $cartDecoratorInterface;
    protected $userId;
    protected $shipping;
    public function __construct($userId , CartDecoratorInterface $cartDecorator , Shipping $shipping)
    {
        $this->cartDecoratorInterface = $cartDecorator;
        $this->userId = $userId;
        $this->shipping = $shipping;
    }

    abstract function totalPrice();
}