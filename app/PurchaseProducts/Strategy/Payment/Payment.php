<?php


namespace App\PurchaseProducts\Strategy\Payment;


class Payment
{
    protected $payment;
    public function __construct(PaymentInterface $payment)
    {
        $this->payment = $payment;
    }

    public function init($request)
    {
        return $this->payment->init($request);
    }

    public function pay()
    {
        return $this->payment->pay();
    }

    public function verify()
    {
        return $this->payment->verify();
    }
}