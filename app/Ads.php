<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Ads
 *
 * @property int $id
 * @property int $ads_position_id
 * @property string $pic
 * @property string|null $final_pic
 * @property string $link_type
 * @property int|null $product_id
 * @property int|null $store_id
 * @property string $description
 * @property string $pay_status
 * @property string|null $payment_type
 * @property string $status
 * @property string|null $expire_date
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AdsStairs[] $payments
 * @property-read int|null $payments_count
 * @property-read \App\AdsPosition $position
 * @property-read \App\ProductSeller|null $product
 * @property-read \App\Store|null $store
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Ads newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ads newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ads query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereAdsPositionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereExpireDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereFinalPic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereLinkType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads wherePayStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ads whereUserId($value)
 * @mixin \Eloquent
 */
class Ads extends Model
{
    protected $table = 'ads';
    protected $fillable = [
        'ads_position_id',
        'status',
        'pic',
        'link_type',
        'product_id',
        'description',
    ];
    public function position(){
        return $this->belongsTo(AdsPosition::class , 'ads_position_id');
    }
    public function product(){
        return $this->belongsTo(ProductSeller::class , 'product_id');
    }

    public function store(){
        return $this->belongsTo(Store::class , 'store_id');
    }
    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function payments(){
        return $this->hasMany(AdsStairs::class , 'ads_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($ads){
            //deleting photo
            $picPath = public_path('image' . DIRECTORY_SEPARATOR . 'ads' . $ads->pic);
            $finalPicPath = public_path('image' . DIRECTORY_SEPARATOR . 'ads' . $ads->final_pic);
            if (\File::exists($picPath)) {
                \File::delete($picPath);
            }
            if (\File::exists($finalPicPath)) {
                \File::delete($finalPicPath);
            }
        });
    } 
}
