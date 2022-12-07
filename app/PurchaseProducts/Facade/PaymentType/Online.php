<?php


namespace App\PurchaseProducts\Facade\PaymentType;


class Online implements PaymentTypeInterface
{

    public function getPayType()
    {
        return  'online';
    }
}