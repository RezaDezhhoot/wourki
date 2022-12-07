<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductRate
 *
 * @property int $id
 * @property int $product_seller_id
 * @property int $user_id
 * @property float $rate
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate whereProductSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductRate whereUserId($value)
 * @mixin \Eloquent
 */
class ProductRate extends Model
{
    protected $table = 'product_seller_rate';

    protected $fillable = [
        'product_seller_id',
        'user_id',
        'rate'
    ];
}
