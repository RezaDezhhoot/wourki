<?php


namespace App\PurchaseProducts\ChainOfResponsibility;


abstract class PurchaseProductBaseHandler implements PurchaseProductChainOfResponsibilityInterface
{
    protected $nextHandler;
    public function setNext(PurchaseProductChainOfResponsibilityInterface $handler)
    {
        $this->nextHandler = $handler;
    }
    public function handle($request = null)
    {
        if($this->nextHandler){
            return $this->nextHandler->handle($request);
        }
        return null;
    }
}