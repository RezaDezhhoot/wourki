<?php


namespace App\PurchaseProducts\Strategy\Payment\Exceptions;


use Throwable;

class CallbackUrlIsNotValidException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}