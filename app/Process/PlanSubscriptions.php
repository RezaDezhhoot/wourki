<?php
namespace App\Process;

use App\Store;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PlanSubscriptions{

    public static function storeHasSubscription($user){
        $now = Carbon::now()->toDateString();
        $count = DB::table('seller_plan_subscription_details')
            ->where('from_date' , '<=' , $now)
            ->where('to_date' , '>=' , $now)
            ->where('user_id' , '=' , $user)
            ->where('plan_type' , 'store')
            ->count();
        return ($count > 0 ) ? true : false;
    }
    public static function storeHasMarketSubscription($user)
    {
        $now = Carbon::now()->toDateString();
        $count = DB::table('seller_plan_subscription_details')
        ->where('from_date', '<=', $now)
            ->where('to_date', '>=', $now)
            ->where('user_id', '=', $user)
            ->where('plan_type', 'market')
            ->count();
        return ($count > 0) ? true : false;
    }

    public static function getActiveSubscriptionIntervalDays(User $user , $type = 'store'){
        $now = Carbon::now()->toDateString();
        $minDate = DB::table('seller_plan_subscription_details')
            ->where('to_date' , '>=' , $now)
            ->where('user_id' , '=' , $user->id)
            ->where('plan_type', $type)
            ->min('from_date');

        $maxDate = DB::table('seller_plan_subscription_details')
            ->where('to_date' , '>=' , $now)
            ->where('user_id' , '=' , $user->id)
            ->where('plan_type', $type)
            ->max('to_date');

        if(!$minDate || !$maxDate){
            return 0;
        }
        $minDateCarbon = Carbon::createFromFormat('Y-m-d' , $minDate);
        $maxDateCarbon = Carbon::createFromFormat('Y-m-d' , $maxDate);
        if($minDateCarbon->toDateString() <= $now){
            $intervalDays = $maxDateCarbon->diffInDays(Carbon::now());
        }else{
            $intervalDays = 0;
        }
        return $intervalDays;
    }

    public static function getLastPlanSubscriptionExpirationDate(User $user , $type = 'store'){
        $now = Carbon::now()->toDateString();
        $maxDate = DB::table('seller_plan_subscription_details')
            ->where('to_date' , '>=' , $now)
            ->where('user_id' , '=' , $user->id)
            ->where('plan_type' , $type)
            ->max('to_date');
        return $maxDate;
    }

    public static function storeHasPremiumSubscription(User $user , $type = 'store'){
        $now = Carbon::now()->toDateString();
        $count = DB::table('seller_plan_subscription_details')
            ->join('seller_plans' , 'seller_plans.id' , '=' , 'seller_plan_subscription_details.plan_id')
            ->where('from_date' , '<=' , $now)
            ->where('to_date' , '>=' , $now)
            ->where('user_id' , '=' , $user->id)
            ->where('seller_plans.price' , '!=' , 0)
            ->where('plan_type' , $type)
            ->count();
        return ($count > 0 ) ? true : false;
    }

    public static function getLastPlanSubscriptionExpirationDatePremium(User $user , $type='store'){
        $now = Carbon::now()->toDateString();
        $maxDate = DB::table('seller_plan_subscription_details')
            ->join('seller_plans' , 'seller_plans.id' , '=' , 'seller_plan_subscription_details.plan_id')
            ->where('seller_plans.price' , '!=' , 0)
            ->where('to_date' , '>=' , $now)
            ->where('user_id' , '=' , $user->id)
            ->where('plan_type', $type)
            ->max('to_date');
        return $maxDate;
    }
}