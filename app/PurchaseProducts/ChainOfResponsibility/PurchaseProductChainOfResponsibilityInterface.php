<?php


namespace App\PurchaseProducts\ChainOfResponsibility;


interface PurchaseProductChainOfResponsibilityInterface
{
    public function setNext(PurchaseProductChainOfResponsibilityInterface $handler);
    public function handle($request = null);
}