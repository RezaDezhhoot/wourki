<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PlanSubscription
 *
 * @method static create(array $array)
 * @property int $id
 * @property int $plan_id
 * @property int|null $store_id
 * @property int|null $user_id
 * @property int $price
 * @property string $plan_type
 * @property string $from_date
 * @property string $to_date
 * @property string|null $pay_id
 * @property string|null $tracking_code
 * @property int $bazar_in_app_purchase
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Plan $plan
 * @property-read \App\Store|null $store
 * @property-read \App\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription query()
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereBazarInAppPurchase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereFromDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription wherePayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereToDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereTrackingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PlanSubscription whereUserId($value)
 * @mixin \Eloquent
 */
class PlanSubscription extends Model
{
    protected $table = 'seller_plan_subscription_details';
    protected $guarded = [];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class , 'store_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
