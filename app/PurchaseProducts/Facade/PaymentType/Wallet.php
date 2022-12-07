<?php


namespace App\PurchaseProducts\Facade\PaymentType;


class Wallet implements PaymentTypeInterface
{

    public function getPayType()
    {
        return 'wallet';
    }
}