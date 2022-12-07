<?php


namespace App\PurchaseProducts\ChainOfResponsibility;


use App\PurchaseProducts\ChainOfResponsibility\Exceptions\UserIsBannedException;
use App\User;

class CheckUserIsBannedOrNot extends PurchaseProductBaseHandler
{
    protected $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function handle($request = null)
    {
        if($this->user->banned == 1){
            throw new UserIsBannedException();
        }
        return parent::handle($request);
    }
}