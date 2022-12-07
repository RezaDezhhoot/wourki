<?php

namespace App\Listeners;

use App\Events\UserVerifyMobile;
use App\Marketer;
use App\ReagentCode;
use App\Setting;
use App\UserReferes;
use App\Wallet;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class AssignGiftToReferred
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
     * @param  UserVerifyMobile $event
     * @return void
     */
    public function handle(UserVerifyMobile $event)
    {
        // get referrer user
        $referrerUser = $event->user;
        // get setting table first row to have gift fee
        $setting = Setting::first();

        // get referrer
        $referred = UserReferes::where('referred_mobile_number', $referrerUser->mobile)
            ->join('users', 'users.id', '=', 'user_refers.referrer_user_id')
            ->where('users.banned', '=', 0)
            ->select('users.id', 'users.mobile', 'users.reagent_code')
            ->first();
        // if referrer user exists, save credit to his wallet
        if ($referred) {
            // save credit to user wallet
            // if (!Marketer::where('user_id', $referred->id)->exists()){
            if($setting->reagented_user_fee != 0)
            Wallet::create([
                'user_id' => $referred->id,
                'cost' => $setting->reagented_user_fee,
                'wallet_type' => 'reagented',
            ]);
        // }

            // Reagent code table store which person refers which persons and how much gift assigned to him
            ReagentCode::create([
                'user_id' => $referrerUser->id,
                'reagent_code' => $referred->reagent_code,
                'reagent_user_fee' => $setting->reagent_user_fee,
                'reagented_user_fee' => $setting->reagented_user_fee,
                'type' => 'reagented',
                'checkout' => 1,
            ]);
        }
    }
}
