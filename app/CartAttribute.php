<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CartAttribute
 *
 * @property int $id
 * @property int $cart_id
 * @property int $product_seller_attribute_id
 * @property-read \App\Product_seller_attribute $attribute
 * @method static \Illuminate\Database\Eloquent\Builder|CartAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CartAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|CartAttribute whereCartId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CartAttribute whereProductSellerAttributeId($value)
 * @mixin \Eloquent
 */
class CartAttribute extends Model
{
    protected  $table = 'cart_attribute';
    protected  $guarded = [];
    public $timestamps = false;

    public function attribute()
    {
        return $this->belongsTo(Product_seller_attribute::class, 'product_seller_attribute_id');
    }
}
