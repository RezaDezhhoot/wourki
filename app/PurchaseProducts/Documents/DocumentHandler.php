<?php

namespace App\PurchaseProducts\Documents;

use App\AccountingDocuments;
use App\BillItem;
use App\Wallet;
use Morilog\Jalali\Jalalian;

class DocumentHandler
{

    public $data;

    public function __construct($request)
    {
        $this->data = $request;
    }

    public function submitBillDocument()
    {
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');
        $billItem = new BillItem();

//        foreach ($this->data as $bill) {
//            if ($bill->status != 'delivered') {
//                Swal::error('اخطار', 'برای ثبت سند وضعیت سند حتما باید تحویل داده باشده');
//                return false;
//            }
//        }

        foreach ($this->data as $bill) {

            $storeName = $bill->store->name;
            $payId = $bill->pay_id == null ? '-' : $bill->pay_id;
            $billTotalPrice = $billItem->getBillItemPrice($bill->id);

            AccountingDocuments::create([
                    'balance' => $billTotalPrice,
                    'description' => 'خرید صورتحساب شماره ' . $bill->id . ' از' . $storeName . ' به مبلغ ' . $billTotalPrice . ' - شماره پیگیری ' . $payId .
                    ' درتاریخ ' . $todayDate . ' ساعت ' . $todayHour,
                    'bill_id' => $bill->id,
                    'type' => 'bill'
                ]
            );

            $sumCommission = 0;

            foreach ($bill->billItems as $billItem) {
//            $billItem->load('product');
//            $billItem->product->load('category');
                $commissionPercent = $billItem->product->category->commission;

                $billItemWithDiscount = ($billItem->price * $billItem->quantity) - ((($billItem->price * $billItem->quantity) * $billItem->discount) / 100);
                $sumCommission += ($billItemWithDiscount * $commissionPercent) / 100;
            }

            $totalPrice = $billTotalPrice - $sumCommission;

            //reduce bill item category commissions
            $this->categoryCommissionReduce($bill, $sumCommission);
            $this->marketCommissionReduce($bill);

            //charge store Wallet
            $this->chargeWallet($bill, $totalPrice);

            $bill->update(['status' => 'delivered']);

        }
        return true;
    }

    public function chargeWallet($bill, $totalPrice)
    {
        $bill->load('billItems');

        $wallet = Wallet::create([
            'user_id' => $bill->store->user['id'],
            'cost' => $totalPrice,
            'wallet_type' => 'input',
            'payable' => 1
        ]);

    }

    public function categoryCommissionReduce($bill, $sumCommission)
    {
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');

        AccountingDocuments::create([
                'balance' => $sumCommission,
                'description' => ' کمیسیون وورکی از صورتحساب شماره ' . $bill->id . ' در تاریخ ' . $todayDate . ' ساعت ' . $todayHour . '',
                'bill_id' => $bill->id,
                'wallet_id' => $bill->id,
                'is_commision' => true,
                'type' => 'checkout'
            ]
        );
    }
    public function marketCommissionReduce($bill){
        $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        $todayHour = Jalalian::forge('now')->format('H:i');
        foreach ($bill->billItems as $billItem) {
            if($billItem->commission_price != 0){
            AccountingDocuments::create(
                [
                    'balance' => $billItem->commission_price,
                    'description' => ' پورسانت بازاریابی محصول/خدمت ' . $billItem->product_name . ' در تاریخ ' . $todayDate . ' ساعت ' . $todayHour . '',
                    'market_id' => $billItem->market_id,
                    'type' => 'commission'
                ]
            );
        }
        }
    }
}
