<?php

namespace App\Listeners;

use App\AccountingDocuments;
use App\Events\SubscriptionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Morilog\Jalali\Jalalian;
use Throwable;

class AddPlanToAccountingDocuments
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
    public function handle(SubscriptionCreated $event)
    {
        // $todayDate = Jalalian::forge('now')->format('%d %B %Y');
        // $todayHour = Jalalian::forge('now')->format('H:i');
        // $subscription = $event->subscription;
        // if($subscription->price != 0){
        // $acc = new AccountingDocuments();
        // $acc->type = 'plan';
        // $acc->plan_id = $subscription->id;
        // $acc->balance = $subscription->price;
        // $acc->description = " صورتحساب خرید اشتراک " . $subscription->plan->plan_name .' در تاریخ ' . $todayDate . ' و در ساعت ' . $todayHour;
        // $acc->save();
        // }

    }
}
