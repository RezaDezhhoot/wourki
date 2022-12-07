<?php

namespace App\Listeners;

use App\Events\ApproveStore;
use App\Events\StoreCreated;
use App\ReagentCode;
use App\Setting;
use App\UserReferes;
use App\Wallet;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Log;
use Throwable;

class AssignStoreCreateGiftToReferrer
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
     * @param  ApproveStore  $event
     * @return void
     */
    public function handle(ApproveStore $event)
    {
        // get referred user
        $referrerUser = $event->user;
        $store = $event->store;
        if (!$store->gift_assigned_to_refferer) {
            // get setting table first row to have gift fee
            $setting = Setting::first();
            try {
                DB::beginTransaction();
                // get referrer
                $referred = UserReferes::where('referred_mobile_number', $referrerUser->mobile)
                    ->join('users', 'users.id', '=', 'user_refers.referrer_user_id')
                    ->where('users.banned', '=', 0)
                    ->select('users.id', 'users.mobile', 'users.reagent_code')
                    ->first();
                // if referrer user exists, save credit to his wallet
                if ($referred) {
                    // save credit to user wallet
                    if ($setting->reagent_user_create_store != 0)
                        Wallet::create([
                            'user_id' => $referred->id,
                            'cost' => $setting->reagent_user_create_store,
                            'wallet_type' => 'reagented',
                        ]);

                    // Reagent code table store which person refers which persons and how much gift assigned to him
                    ReagentCode::create([
                        'user_id' => $referrerUser->id,
                        'reagent_code' => $referred->reagent_code,
                        'reagent_user_fee' => $setting->reagent_user_create_store,
                        'reagented_user_fee' => 0,
                        'type' => 'create_store',
                        'checkout' => 1,
                    ]);
                }
                $store->gift_assigned_to_refferer = true;
                $store->save();
                DB::commit();
            } catch (Throwable $e) {
                DB::rollBack();
                Log::info($e->getMessage());
            }
        } else {
            //do nothing
        }
    }
}
