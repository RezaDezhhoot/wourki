<?php


namespace App\PurchaseProducts\Facade\PaymentType;


class PaymentType
{
    private $payType;
    const PAYMENT_TYPE_WALLET = 'wallet';
    const PAYMENT_TYPE_ONLINE = 'online';
    public function __construct(PaymentTypeInterface $paymentType)
    {
        $this->payType = $paymentType;
    }
    public function getPayType(){
        return $this->payType->getPayType();
    }
}