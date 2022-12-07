<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Attribute
 *
 * @property int $id
 * @property string $type
 * @property string $store_type
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product_seller_attribute[] $productSellerAttributes
 * @property-read int|null $product_seller_attributes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attribute whereType($value)
 * @mixin \Eloquent
 */
class Attribute extends Model
{
    protected $table = 'attribute';
    public $timestamps = false;
    protected $fillable = ['type' , 'store_type'];
    public function productSellerAttributes()
    {
        return $this->hasMany(Product_seller_attribute::class , 'attribute_id');
    }
}
