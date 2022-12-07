<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Cart
 *
 * @property int $id
 * @property int $user_id
 * @property int $store_id
 * @property int $product_seller_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CartAttribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \App\ProductSeller $product
 * @property-read \App\Store $store
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereProductSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cart whereUserId($value)
 * @mixin \Eloquent
 */
class Cart extends Model
{
    protected  $table = 'cart';
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductSeller::class, 'product_seller_id');
    }

    public function attributes()
    {
        return $this->hasMany(CartAttribute::class , 'cart_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class , 'store_id');
    }

}
