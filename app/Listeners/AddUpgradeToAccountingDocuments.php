<?php

namespace App\Listeners;

use App\AccountingDocuments;
use App\Events\UpgradeCreated;
use App\ProductSeller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Morilog\Jalali\Jalalian;

class AddUpgradeToAccountingDocuments
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
     * @param  object  $event
     * @return void
     */
    public function handle(UpgradeCreated $event)
    {
    //     //lets add this upgrade to accounting documents
    //     $upgrade = $event->upgrade;
    //     $name = $upgrade->upgradable->name;
    //     $type = "فروشگاه";
    //     $payment_type = "پرداخت درون برنامه ای";
    //     $todayDate = Jalalian::forge('now')->format('%d %B %Y');
    //     $todayHour = Jalalian::forge('now')->format('H:i');
    //     if($upgrade->pay_type == "admin")
    //         $payment_type = "مدیریت سایت";
    //     if($upgrade->pay_type == "wallet")
    //         $payment_type = "کیف پول";
    //     if($upgrade->pay_type == "online")
    //         $payment_type = "پرداخت آنلاین";
    //     if($upgrade->upgradable_type == ProductSeller::class){
    //         $type = "محصول / خدمت";
    //     }
    //     $acc = new AccountingDocuments();
    //     $acc->type = 'upgrade';
    //     $acc->upgrade_id = $upgrade->id;
    //     $acc->balance = $upgrade->price;
    //     $acc->description = " صورتحساب ارتقا ".$type." ".$name." از طریق ".$payment_type . ' در تاریخ ' . $todayDate . ' و در ساعت ' . $todayHour;
    //     $acc->save();
    }
}
