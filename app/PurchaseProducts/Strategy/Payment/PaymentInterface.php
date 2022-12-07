<?php


namespace App\PurchaseProducts\Strategy\Payment;


interface PaymentInterface
{
    public function init($request);
    public function pay();
    public function verify();
}