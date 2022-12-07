<?php

namespace App\Listeners;

use App\Events\UserVerifyMobile;
use App\ReagentCode;
use App\Setting;
use App\UserReferes;
use App\Wallet;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class AssignGiftToReferrer
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
        // get referred user
        $referrerUser = $event->user;
        // get setting table first row to have gift fee
        $setting = Setting::first();

        // get referrer user
        $referred = UserReferes::where('referred_mobile_number', $referrerUser->mobile)
            ->join('users', 'users.id', '=', 'user_refers.referrer_user_id')
            ->where('users.banned', '=', 0)
            ->select('users.id', 'users.mobile', 'users.reagent_code')
            ->first();
        if($referred && $setting->reagent_user_fee != 0){
            // if referrer user exists, assign gift to referred user
            
            Wallet::create([
                'user_id' => $referrerUser->id,
                'cost' => $setting->reagent_user_fee,
                'wallet_type' => 'reagented'
            ]);

        }


    }
}
