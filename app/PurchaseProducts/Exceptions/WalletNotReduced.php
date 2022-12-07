<?php


namespace App\PurchaseProducts\Exceptions;


use Throwable;

class WalletNotReduced extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}