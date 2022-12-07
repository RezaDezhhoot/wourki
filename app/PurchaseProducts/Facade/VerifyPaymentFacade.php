<?php


namespace App\PurchaseProducts\Facade;


use App\Address;
use App\Cart;
use App\PurchaseProducts\Facade\Exceptions\AddressIsNotStoreInSessionException;
use App\PurchaseProducts\SaveBill\BillHandler;
use App\PurchaseProducts\SaveBill\Payments\Wallet;
use App\PurchaseProducts\SaveBill\SaveBill;
use App\PurchaseProducts\SaveBill\SaveBillItem;
use App\PurchaseProducts\SaveBill\SaveBillItemAttributes;
use App\PurchaseProducts\Strategy\Payment\Online;
use App\PurchaseProducts\Strategy\Payment\Payment;
use App\UsedDiscount;

class VerifyPaymentFacade
{
    private $address;
    public function __construct()
    {
        if(!request()->session()->has('address')){
            throw new AddressIsNotStoreInSessionException('آدرس نامعتبر است.');
        }
        $this->address = Address::join('city', 'city.id', 'address.city_id')
            ->join('province', 'province.id', 'city.province_id')
            ->where('address.id', request()->session()->get('address'))
            ->select('city.id as city_id', 'city.name as city_name', 'province.name as province_name', 'address.id as address_id', 'address.latitude', 'address.longitude', 'address.address')
            ->first();
    }

    public function verifyPayment()
    {
        $onlinePayment = new Payment(new Online());
        // verify payment
        $paymentResult = $onlinePayment->verify();
        $user = auth()->guard('web')->user();
        if(session()->has('used_discount_ids')){
            $usedDiscounts = json_decode(session()->get('used_discount_ids'));
            UsedDiscount::whereIn('id' , $usedDiscounts)->update([
                'status' => 'approved'
            ]);
        }
        $billHandler = new BillHandler($user, $this->address, new Wallet());
        $billHandler->save();

        return true;

    }
}