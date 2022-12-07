<?php

namespace App\Listeners;

use App\Bill;
use App\Setting;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AssignFirstBuyGift
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
     * @param  BillConfirmed  $event
     * @return void
     */
    public function handle($event)
    {
        $bills = $event->bills;
        $ids = $bills->pluck('id');
        foreach($bills as $bill){
            $exists = Bill::whereNotIn('id' , $ids)->where('confirmed' , 1)->where('user_id' , $bill->user_id)->exists();
            if(!$exists){
                // means this is first buy of this user so lets assign gift to him/her
                $setting = Setting::first();
                if($setting->first_buy_gift != 0)
                User::find($bill->user_id)->wallet()->create([
                    'cost' => $setting->first_buy_gift,
                    'wallet_type' => 'first_buy_gift'
                ]);

            }
        }

    }
}
