<?php


namespace App\PurchaseProducts\ChainOfResponsibility\Exceptions;


use Throwable;

class ProductStockIsNotEnough extends \Exception
{
    private $additionalData = [];
    public function __construct($message = "", $code = 0, Throwable $previous = null , $additionalData = [])
    {
        $this->additionalData = $additionalData;
        parent::__construct($message, $code, $previous);
    }

    public function getAdditionalData(){
        return $this->additionalData;
    }
}