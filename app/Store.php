<?php

namespace App;

use App\Process\PlanSubscriptions;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Store
 *
 * @property int $id
 * @property int $user_id
 * @property string $slogan
 * @property int $address_id
 * @property int $guild_id
 * @property string|null $slug
 * @property string $name
 * @property string $user_name
 * @property int $min_pay
 * @property string $store_type
 * @property string $status
 * @property int $visible
 * @property string $about
 * @property string $phone_number
 * @property string $phone_number_visibility
 * @property string $mobile_visibility
 * @property string|null $reject_reason
 * @property int $total_hits
 * @property string $pay_type
 * @property string $activity_type
 * @property string|null $shaba_code
 * @property int $notified_finishing_subscription_plan
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Address $address
 * @property-read mixed $has_subscription
 * @property-read mixed $marked_as_submitted_accounting_documents
 * @property-read \App\Guild $guild
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Store_photo[] $photos
 * @property-read int|null $photos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PlanSubscription[] $plans
 * @property-read int|null $plans_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductSeller[] $products
 * @property-read int|null $products_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rate[] $rate
 * @property-read int|null $rate_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Store findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereAbout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereActivityType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereGuildId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereInstagramAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereInstagramVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereMinPay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereMobileVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereNotifiedFinishingSubscriptionPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePayType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store wherePhoneNumberVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereRejectReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereShabaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereSlogan($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereTelegramAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereTelegramChannelVisibility($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereThumbnailPhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereTotalHits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Store whereVisible($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    protected $table = 'store';
    protected $guarded = [];

    use Sluggable;
    protected $appends = [
        'marked_as_submitted_accounting_documents',
        'has_subscription'
    ];

    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name',
            ]
        ];
    }

    public function products()
    {
        if($this->store_type != 'market')
        return $this->hasMany(ProductSeller::class, 'store_id');
        else {
            return $this->belongsToMany(ProductSeller::class , 'market_product' , 'market_id' , 'product_id');
        }
    }

    public function photo()
    {
        return $this->hasOne(Store_photo::class, 'store_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    public function rate()
    {
        return $this->hasMany(Rate::class, 'store_id');
    }

    public function plans()
    {
        return $this->hasMany(PlanSubscription::class, 'store_id');
    }

    public function guild()
    {
        return $this->belongsTo(Guild::class, 'guild_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function upgrades(){
        return $this->morphMany(Upgrade::class , 'upgradable');
    }
    public function getMarkedAsSubmittedAccountingDocumentsAttribute()
    {
        $count = DB::table('bill')
            ->join('accounting_documents as acc_doc', 'acc_doc.bill_id', '=', 'bill.id')
            ->where('acc_doc.type', '=', 'bill')
            ->where('bill.status', '=', 'delivered')
            ->where('bill.pay_type', '=', 'online')
            ->whereRaw('(
                (
                    IFNULL((
                        select sum(quantity * (price - (price * discount / 100) ) )
                        from bill_item
                        where bill_id = bill.id
                    ) , 0)
                     +
                     IFNULL((
                        select sum(extra_price)
                        from bill_item_attribute
                        join bill_item as bi3 on bi3.id = bill_item_attribute.bill_item_id
                        where bi3.bill_id = bill.id
                     ) , 0)
                )     
                > 
                IFNULL(
                (
                    select sum(price)
                    from checkouts
                    where store_id = bill.store_id
                ) , 0)
            )')
            ->where('bill.store_id', '=', $this->attributes['id'])
            ->count();
        return ($count > 0);
    }

    public function getHasSubscriptionAttribute(){
        $subscription = PlanSubscriptions::storeHasSubscription($this->user_id);
        return $subscription;
    }
}
