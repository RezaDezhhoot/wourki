<?php


namespace App\PurchaseProducts\ChainOfResponsibility\Exceptions;


use Throwable;

class UserIsBannedException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}