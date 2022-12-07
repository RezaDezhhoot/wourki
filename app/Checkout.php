<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Checkout
 *
 * @method static create(array $data)
 * @property int $id
 * @property int $price
 * @property int|null $store_id
 * @property int|null $marketer_id
 * @property string $pay_id
 * @property int $wallet_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\AccountingDocuments|null $accounting
 * @property-read \App\CheckoutRequests|null $checkoutRequest
 * @property-read \App\Store|null $store
 * @property-read \App\Wallet $wallet
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout query()
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout whereMarketerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout wherePayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Checkout whereWalletId($value)
 * @mixin \Eloquent
 */
class Checkout extends Model
{
    protected $table = 'checkouts';
    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function checkoutRequest()
    {
        return $this->hasOne(CheckoutRequests::class, 'checkout_id');
    }

    public function accounting()
    {
        return $this->hasOne(AccountingDocuments::class, 'checkout_id');
    }

    public function wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(wallet::class);
    }


}
