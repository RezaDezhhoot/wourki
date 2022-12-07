<?php

namespace App\Listeners;

use App\Events\UserVerifyMobile;
use App\Setting;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AssignRegisterGiftToUser
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
     * @param  UserVerifyMobile  $event
     * @return void
     */
    public function handle(UserVerifyMobile $event)
    {
        $giftPrice = Setting::first()->register_gift;
        if($giftPrice != 0)
        $event->user->wallet()->create([
            'cost' => $giftPrice,
            'wallet_type' => 'register_gift'
        ]);
    }
}
