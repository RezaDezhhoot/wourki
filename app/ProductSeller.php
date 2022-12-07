<?php

namespace App;

use File;
use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductSeller
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $price
 * @property int $discount
 * @property int $quantity
 * @property int $visible
 * @property int $category_id
 * @property int $store_id
 * @property string $status
 * @property int $hint
 * @property int $is_vip
 * @property int $product_without_photo_notified
 * @property int $guarantee_mark
 * @property string $shipping_price_to_tehran
 * @property string $shipping_price_to_other_towns
 * @property int $deliver_time_in_tehran
 * @property int $deliver_time_in_other_towns
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product_seller_attribute[] $attributes
 * @property-read int|null $attributes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BillItem[] $billItem
 * @property-read int|null $bill_item_count
 * @property-read \App\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductSellerComment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Product_seller_photo[] $photos
 * @property-read int|null $photos_count
 * @property-read \App\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereDeliverTimeInOtherTowns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereDeliverTimeInTehran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereGuaranteeMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereHint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereIsVip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereProductWithoutPhotoNotified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereShippingPriceToOtherTowns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereShippingPriceToTehran($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSeller whereVisible($value)
 * @mixin \Eloquent
 */
class ProductSeller extends Model
{
    protected $table = 'product_seller';
    protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(Product_seller_photo::class , 'seller_product_id');
    }
    public function comments()
    {
        return $this->hasMany(ProductSellerComment::class , 'product_seller_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class , 'store_id');
    }

    public function attributes()
    {
        return $this->hasMany(Product_seller_attribute::class, 'product_seller_id');
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function billItem()
    {
        return $this->hasMany(BillItem::class, 'product_id');
    }
    public function upgrades(){
        return $this->morphMany(Upgrade::class , 'upgradable');
    }
    public function markets(){
        return $this->belongsToMany(Store::class , 'market_product' , 'product_id' , 'market_id');
    }
    public function getDiscountsPaginated($user_id){
        if($user_id == $this->store->user_id)
        return Discount::whereIn('discountable_type' , [$this->store->store_type , 'product-sending'])->where('discountable_id' , $this->id)->where('admin_made' , false)->paginate(20);
        return [];
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            //deleting photos
            foreach ($product->photos as $photo) {
                $path = public_path('image' . DIRECTORY_SEPARATOR . 'product_seller_photo' . DIRECTORY_SEPARATOR . $photo->file_name);
                if(File::exists($path)){
                    File::delete($path);
                }
            }
        });
    }
}
