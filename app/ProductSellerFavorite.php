<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductSellerFavorite
 *
 * @property int $user_id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerFavorite newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerFavorite newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerFavorite query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerFavorite whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerFavorite whereUserId($value)
 * @mixin \Eloquent
 */
class ProductSellerFavorite extends Model
{
    protected $table = 'product_seller_favorite';
    protected $fillable = [
        'product_id', 'user_id'
    ];
}
