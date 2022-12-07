<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Request;

/**
 * App\User
 *
 * @method static select(string[] $array)
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $password
 * @property string $mobile
 * @property int $mobile_confirmed
 * @property string|null $verify_mobile_token
 * @property string|null $shaba_code
 * @property string|null $gcm_code
 * @property string|null $reset_password_token
 * @property string|null $email
 * @property string|null $remember_token
 * @property int|null $verify_forget_password_token
 * @property int $banned
 * @property string|null $reagent_code
 * @property int $become_marketer
 * @property string|null $last_login_datetime
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property int|null $referrer_user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Address[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Ads[] $ads
 * @property-read int|null $ads_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Bill[] $bills
 * @property-read int|null $bills_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Cart[] $carts
 * @property-read int|null $carts_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CheckoutRequests[] $checkoutRequest
 * @property-read int|null $checkout_request_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read int|null $clients_count
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductSeller[] $favoriteProducts
 * @property-read int|null $favorite_products_count
 * @property-read mixed $total_credit
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Marketer[] $marketer
 * @property-read int|null $marketer_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Message[] $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[]
 *     $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PlanSubscription[] $plans
 * @property-read int|null $plans_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductRate[] $productRate
 * @property-read int|null $product_rate_count
 * @property-read \App\Store|null $store
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Wallet[] $wallet
 * @property-read int|null $wallet_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBanned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBecomeMarketer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGcmCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginDatetime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMobileConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReagentCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereReferrerUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereResetPasswordToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereShabaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerifyForgetPasswordToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereVerifyMobileToken($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use Notifiable , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected  $appends = [
        'total_credit'
    ];
    protected $fillable = [
        'first_name', 'last_name', 'mobile', 'password', 'mobile_confirmed', 'verify_mobile_token', 'gsm_code', 'thumbnail_photo',
        'reset_password_token', 'email', 'remember_token', 'reagent_code', 'shaba_code', 'become_marketer', 'card', 'returnPayType',
        'about'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function comments(){
        return $this->hasMany(User::class , 'user_id');
    }

    public function bills()
    {
        return $this->hasMany(Bill::class , 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class , 'user_id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class, 'user_id');
    }
    public function store()
    {
        return $this->hasOne(Store::class, 'user_id');
    }
    public function favoriteProducts()
    {
        return $this->belongsToMany(ProductSeller::class , 'product_seller_favorite' , 'user_id' , 'product_id');
    }

    public function marketer()
    {
        return $this->hasMany(Marketer::class, 'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class , 'user_id');
    }

    public function addresses(){
        return $this->hasMany(Address::class , 'user_id');
    }

    public function plans(){
        return $this->hasMany(PlanSubscription::class , 'user_id');
    }

    public function getTotalCreditAttribute()
    {
        $credit = Wallet::where('user_id', $this->id)
            ->sum('cost');
        return $credit;
    }

    public function productRate()
    {
        return $this->hasMany(ProductRate::class, 'user_id');
    }

    public function wallet()
    {
        return $this->hasMany(Wallet::class, 'user_id');
    }

    public function ads()
    {
        return $this->hasMany(Ads::class, 'user_id');
    }

    public function checkoutRequest(): HasMany
    {
        return $this->hasMany(CheckoutRequests::class, 'user_id');
    }
    public function market(){
        return $this->hasOne(Store::class , 'user_id')->where('store_type' , 'market');
    }
}
