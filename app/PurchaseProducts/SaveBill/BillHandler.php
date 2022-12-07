<?php

namespace App\PurchaseProducts\SaveBill;

use App\Address;
use App\Cart;
use App\PurchaseProducts\SaveBill\Payments\Wallet;

class BillHandler
{

    protected $storeIds;
    protected $user;
    protected $address;
    protected $wallet;
    protected $discount;

    public function __construct($user, $address,$wallet , $discount = null)
    {
        $this->user = $user;
        $this->address = $address;
        $this->wallet = $wallet;
        $this->discount = $discount;
    }

    public function save()
    {
        $storeIds = Cart::where('user_id', $this->user->id)
            ->select('store_id', 'id')
            ->get();

        // get cart items that belongs to the authenticated user
        // due to the fact that the buyer may have ordered from multiple sellers at the same time, the invoices must be broken down by different seller
        $storeIds = $storeIds->groupBy('store_id', 'id');
        foreach ($storeIds as $storeId => $store) {
            $savedBill = new SaveBill($this->user->id, $storeId, $this->address->address_id, $this->wallet);
            $savedBill = $savedBill->save();
            $savedBillItems = new SaveBillItem($savedBill , $this->discount);
            $savedBillItems = $savedBillItems->save();
            foreach ($savedBillItems as $billItem) {
                $savedBillItemAttr = new SaveBillItemAttributes($billItem, $storeId , $this->discount);
                $savedBillItemAttr->save();
            }
        }

        // truncate user cart
        $this->user->carts()->delete();

        return true;
    }
}