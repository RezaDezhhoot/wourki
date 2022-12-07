<?php


namespace App\PurchaseProducts\Facade\Api;


use App\Address;
use App\Cart;
use App\Discount;
use App\PurchaseProducts\ChainOfResponsibility\CheckProductStock;
use App\PurchaseProducts\ChainOfResponsibility\CheckUserIsBannedOrNot;
use App\PurchaseProducts\ChainOfResponsibility\CheckWalletStock;
use App\PurchaseProducts\Decorator\CartDecorator;
use App\PurchaseProducts\Decorator\ShippingPrice;
use App\PurchaseProducts\Exceptions\WalletRestriction;
use App\PurchaseProducts\Facade\PaymentType\PaymentType;
use App\PurchaseProducts\SaveBill\BillHandler;
use App\PurchaseProducts\SaveBill\Payments\Wallet;
use App\PurchaseProducts\SaveBill\SaveBill;
use App\PurchaseProducts\SaveBill\SaveBillItem;
use App\PurchaseProducts\SaveBill\SaveBillItemAttributes;
use App\PurchaseProducts\Strategy\Payment\Online;
use App\PurchaseProducts\Strategy\Payment\Payment;
use App\PurchaseProducts\Strategy\Shipping\Shipping;
use App\PurchaseProducts\Strategy\Shipping\Tehran;
use App\PurchaseProducts\Strategy\Shipping\Towns;
use App\Setting;
use App\User;
use Larabookir\Gateway\Gateway;

class SaveBillFacade
{
    private $user;
    private $address;
    private $payType;
    private $discount;
    
    public function __construct($userId, $addressId, PaymentType $paymentType , ?Discount $discount)
    {
        $this->user = User::find($userId);
        $this->address = Address::join('city', 'city.id', 'address.city_id')
            ->join('province', 'province.id', 'city.province_id')
            ->where('address.id', $addressId)
            ->select('city.id as city_id', 'city.name as city_name', 'province.name as province_name', 'address.id as address_id', 'address.latitude', 'address.longitude', 'address.address')
            ->first();
        $this->payType = $paymentType;
        $this->discount = $discount;
    }

    public function save(){
        $shippingPlace = $this->address->city_id == 118 ? new Tehran($this->discount) : new Towns($this->discount);
        // calculate total invoice price including shipping cost
        $shipping = new Shipping($shippingPlace, $this->user->id);
        $cartDecorator = new CartDecorator($this->user->id, $shipping, $this->discount);
        $shippingDecorator = new ShippingPrice($this->user->id, $cartDecorator, $shipping);
        $totalCartPrice = $shippingDecorator->totalPrice();

        if ($this->payType->getPayType() == PaymentType::PAYMENT_TYPE_WALLET) {


            // if the product stock or wallet stock was insufficient an error should displayed to user
            // this is done using the chain of responsibility design pattern
            $checkUserIsBannedOrNot = new CheckUserIsBannedOrNot($this->user);
            $checkProductStockHandler = new CheckProductStock();
            $checkWalletStockHandler = new CheckWalletStock($totalCartPrice);
            $checkUserIsBannedOrNot->setNext($checkProductStockHandler);
            $checkProductStockHandler->setNext($checkWalletStockHandler);
            $checkUserIsBannedOrNot->handle([
                'user_id' => $this->user->id,
            ]);
            // if user wallet and product stock was sufficient, invoice amount should be deducted from user wallet
            // this can only happen if the payment method is through the wallet
            // pay through the wallet should be done by strategy design pattern
            $walletPayment = new Payment(new \App\PurchaseProducts\Strategy\Payment\Wallet());
            $walletPayment->init([
                'user_id' => $this->user->id,
                'wallet_type' => \App\PurchaseProducts\Strategy\Payment\Wallet::OUTPUT,
                'tracking_code' => null,
                'cost' => -1 * $totalCartPrice,
            ]);
            $walletPayment->pay();
            $walletPayment->verify();

            $billHandler = new BillHandler($this->user, $this->address, new Wallet(), $this->discount);
            $billHandler->save();

            // returning true result when all actions was successfully
            // else where redirect user to payment gateway
            return true;
        } else if($this->payType->getPayType() == PaymentType::PAYMENT_TYPE_ONLINE){
            // if the product stock was insufficient an error should displayed to user
            // because of payment type is online, unlike previous block wallet stock doesn't check
            // this is done using the chain of responsibility design pattern
            $checkUserIsBannedOrNot = new CheckUserIsBannedOrNot($this->user);
            $checkProductStockHandler = new CheckProductStock();
            $checkUserIsBannedOrNot->setNext($checkProductStockHandler);
            $checkUserIsBannedOrNot->handle([
                'user_id' => $this->user->id,
            ]);
            return true;
        }
    }
}