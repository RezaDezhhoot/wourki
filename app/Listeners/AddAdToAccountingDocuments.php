<?php

namespace App\Listeners;

use App\AccountingDocuments;
use App\Events\AdCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Morilog\Jalali\Jalalian;

class AddAdToAccountingDocuments
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
    public function handle(AdCreated $event)
    {
        // $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        // $todayHour = Jalalian::forge('now')->format('H:i');
        // $price = $event->price;
        // $ad = $event->ad;
        // if ($price != 0) {
        //     $acc = new AccountingDocuments();
        //     $acc->type = 'ad';
        //     $acc->ads_id = $ad->id;
        //     $acc->balance = $price;
        //     $acc->description = " صورتحساب خرید تبلیغات " . ' در تاریخ ' . $todayDate . ' و در ساعت ' . $todayHour;
        //     $acc->save();
        // }
    }
}
