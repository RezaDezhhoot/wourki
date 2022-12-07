<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\CheckoutRequests
 *
 * @method static create(array $array)
 * @property mixed user
 * @property mixed checkout
 * @property int $id
 * @property int $user_id
 * @property int|null $checkout_id
 * @property int $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests query()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests whereCheckoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CheckoutRequests whereUserId($value)
 * @mixin \Eloquent
 */
class CheckoutRequests extends Model
{
    protected $table = 'checkout_requests';

    protected $fillable = ['user_id', 'checkout_id', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkout(): BelongsTo
    {
        return $this->belongsTo(Checkout::class, 'checkout_id');
    }
}
