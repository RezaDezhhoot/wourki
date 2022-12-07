<?php


namespace App\PurchaseProducts\Strategy\Payment;


use App\PurchaseProducts\Strategy\Payment\Exceptions\CallbackUrlIsNotValidException;
use App\PurchaseProducts\Strategy\Payment\Exceptions\PriceIsNotValidException;
use Larabookir\Gateway\Gateway;

class Online implements PaymentInterface
{
    private $cost;
    private $gateway;
    private $callbackUrl;

    /*
     * request included below indices
     * wallet_type (This is only used for wallet payments)
     * gateway
     * callback_url
     * address_id
     * */
    public function init($request)
    {
        if (!isset($request['cost']) || !is_numeric($request['cost'])) {
            throw new PriceIsNotValidException();
        }
        // detect if callback address is a url or not
        if (!filter_var($request['callback_url'], FILTER_VALIDATE_URL)) {
            throw new CallbackUrlIsNotValidException();
        }
        $this->gateway = $request['gateway'];
        $this->callbackUrl = $request['callback_url'];
        $this->cost = $request['cost'];
        request()->session()->put('gateway_cost', $this->cost);
        request()->session()->put('gateway_callback_url', $this->callbackUrl);
    }

    public function pay()
    {
        $this->gateway->setCallback($this->callbackUrl);
        $this->gateway->price($this->cost)
            ->ready();
        return $this->gateway->redirect();
    }

    public function verify()
    {
        $gateway = Gateway::verify();
        $trackingCode = $gateway->trackingCode();
        $refId = $gateway->refId();

        $obj = new \stdClass();
        $obj->price = request()->session()->get('gateway_cost');
        $obj->tracking_code = $trackingCode;
        $obj->ref_id = $refId;

        return $obj;
    }

}