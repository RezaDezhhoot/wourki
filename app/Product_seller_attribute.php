<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Product_seller_attribute
 *
 * @property int $id
 * @property int $product_seller_id
 * @property int $attribute_id
 * @property string $title
 * @property int $extra_price
 * @property int $deleted
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Attribute $attribute
 * @property-read \App\ProductSeller $product
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereAttributeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereExtraPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereProductSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_attribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product_seller_attribute extends Model
{
    protected $table = 'product_seller_attribute';
    protected $guarded = [];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class , 'attribute_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductSeller::class, 'product_seller_id');
    }
}
