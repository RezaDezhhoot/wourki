<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Product_seller_photo
 *
 * @property int $id
 * @property string $file_name
 * @property int $seller_product_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\ProductSeller $product
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo whereSellerProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product_seller_photo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product_seller_photo extends Model
{
    protected $table = 'product_seller_photo';

    public function product()
    {
        return $this->belongsTo(ProductSeller::class , 'seller_product_id');
    }
}
