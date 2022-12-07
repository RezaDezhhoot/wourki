<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AdsStairs
 *
 * @property int $id
 * @property int $ads_id
 * @property string|null $tracking_code
 * @property string|null $ref_id
 * @property string $payment_type
 * @property string $pay_date
 * @property string $initial_pay
 * @property string $price
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Ads $ads
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs query()
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs whereAdsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs whereInitialPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs wherePayDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs whereRefId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs whereTrackingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AdsStairs whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AdsStairs extends Model
{
    protected $table= 'ads_stairs';
    protected $fillable = [
        'tracking_code',
        'ref_id',
        'payment_type',
        'pay_date',
        'initial_pay',
        'price',
    ];
    public function ads(){
        return $this->belongsTo(Ads::class , 'ads_id');
    }
}
