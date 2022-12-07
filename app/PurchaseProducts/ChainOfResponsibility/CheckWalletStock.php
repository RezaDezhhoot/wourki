<?php


namespace App\PurchaseProducts\ChainOfResponsibility;

use App\PurchaseProducts\Exceptions\UserNotPassedException;
use App\PurchaseProducts\ChainOfResponsibility\Exceptions\WalletStockIsNotEnough;
use App\Wallet;

// this class check if user's wallet stock is enough or not
// if it's not enough redirect throw an error
class CheckWalletStock extends PurchaseProductBaseHandler
{
    protected $totalPrice;
    public function __construct($totalPrice)
    {
        $this->totalPrice = $totalPrice;
    }

    public function handle($request = null)
    {
        if(!isset($request['user_id'])){
            throw new UserNotPassedException('User id not passed');
        }
        // calculate user's wallet amount
        $userWallet = Wallet::where('user_id', $request['user_id'])
            ->sum('cost');
        // if user wallet stock is not enough throw an exception
        if($userWallet < $this->totalPrice){
            throw new WalletStockIsNotEnough('موجودی کیف پول کمتر از مقدار مورد نیاز است.');
        }
        parent::handle($request);

    }
}