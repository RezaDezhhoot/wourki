<?php

namespace App\Listeners;

use App\AccountingDocuments;
use App\Events\SubmitBill;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Morilog\Jalali\Jalalian;

class SaveBillInAccountingDocument
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubmitBill  $event
     * @return void
     */
    public function handle(SubmitBill $event)
    {
        $bill = $event->bill;
        $trackingCode = $bill->pay_id;
        if (!$trackingCode) {
            $trackingCode = '';
        } else {
            $trackingCode = "شماره پیگیری {$trackingCode}";
        }
        $date = Jalalian::forge($bill->created_at)->format('%d %B %Y');
        $time = Jalalian::forge($bill->created_at)->format('time');
        $description = "خرید صورت حساب شماره {$bill->id} از فروشگاه {$bill->store_name} به مبلغ {$bill->bill_price} - " . $trackingCode . "- در تاریخ {$date} ساعت {$time}";
        $document = new AccountingDocuments();
        $document->balance = $bill->bill_price;
        $document->description = $description;
        $document->type = 'bill';
        $document->bill_id = $bill->id;
        $document->save();
    }
}
