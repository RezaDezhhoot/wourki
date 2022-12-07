<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Wallet
 *
 * @method static where(string $string, $id)
 * @method static join(string $string, string $string1, string $string2, string $string3)
 * @method static create(array $array)
 * @property int $id
 * @property int $user_id
 * @property float $cost
 * @property string $wallet_type
 * @property string|null $tracking_code
 * @property int $payable
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Checkout|null $checkout
 * @property-read \Illuminate\Database\Eloquent\Collection|Wallet[] $reducedFrom
 * @property-read int|null $reduced_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Wallet[] $reducedItem
 * @property-read int|null $reduced_item_count
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet wherePayable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereTrackingCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Wallet whereWalletType($value)
 * @mixin \Eloquent
 */
class Wallet extends Model
{
    protected $table = 'wallet';
    protected $guarded = [];

    public function reducedItem(): BelongsToMany
    {
        return $this->belongsToMany(Wallet::class, 'wallet_reduce', 'ReducedItem', 'ReducedFrom')->withPivot(['Amount'])->withTimestamps();
    }

    public function reducedFrom(): BelongsToMany
    {
        return $this->belongsToMany(Wallet::class, 'wallet_reduce', 'ReducedFrom', 'ReducedItem')->withPivot(['Amount'])->withTimestamps();
    }

    public function checkout()
    {
        return $this->hasOne(Checkout::class, 'wallet_id');
    }
}
