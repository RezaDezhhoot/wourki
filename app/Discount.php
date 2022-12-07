<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Log;

/**
 * App\Comment
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $discountable_type
 * @property int $discountable_id
 * @property date $start_date
 * @property date $end_date
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at


 * @mixin \Eloquent
 */
class Discount extends Model
{
    const types = ['guild', 'category', 'service', 'product', 'store' , 'all' , 'all-product' , 'all-service' , 'all-ads' , 'all-plans' , 'all-upgrade' , 'ad' , 'plan' , 'upgrade' , 'all-sending' , 'store-sending' , 'product-sending'];
    protected $fillable = ['code' , 'name' , 'description' , 'discountable_type' , 'discountable_id' , 'start_date' , 'end_date' , 'percentage' , 'type' , 'min_price' , 'max_price'];
    public $table = 'discounts';
    public static function chackForExistance($type , $id){
        if($type == "guild"){
            return Guild::where('id' , $id)->exists();
        }
        if($type == "category"){
            return Category::where('id' , $id)->exists();
        }
        if($type == "product" || $type == "service"){
            return ProductSeller::where('product_seller.id' , $id)->join('store' , 'product_seller.store_id' , '=' , 'store.id')
                ->where('store_type' , $type)->exists();
        }
        if($type == "store"){
            return Store::where('id' , $id)->exists();
        }
        if($type == "ad"){
            return AdsPosition::where('id' , $id)->exists();
        }
        if($type == "plan"){
            return Plan::where('status' , 'show')->where('id' , $id)->exists();
        }
        if($type == "upgrade"){
            return UpgradePosition::where('id' , $id)->exists();
        }
        return false;
    }
    public function applyOn($price){
        if((is_null($this->min_price) || $price >= $this->min_price) && (is_null($this->max_price) || $price <= $this->max_price)){
            if($this->type == "percentage"){
                return $price - ($price * $this->percentage) / 100;
            }
            else{
                if($price - $this->percentage < 0){
                    return 0;
                }
                return $price - $this->percentage;
            }
        }
        return $price;
        
    }
    public static function getDiscountFor($code , $type , $id){
        $discount = Discount::where('code' , $code)->whereDate('start_date' , '<=' , Carbon::now())
            ->whereDate('end_date' , '>=' , Carbon::now())->first();
        if (!$discount) return null;
        if($id == null){
            if($discount->discountable_type == $type || $discount->discountable_type == ("all-" . $type)
                || $discount->discountable_type == 'all' || $discount->discountable_type == "category" || $discount->discountable_type == "guild" || $discount->discountable_type == 'store'
            ) return $discount;
            else if($type == "sending" && ($discount->discountable_type == "store-sending" || $discount->discountable_type == "product-sending"))
                return $discount;
            else
            return null;
        }
        //for product and service
        if($type == 'product' || $type == 'service'){
            //checking for all
            if($discount->discountable_type == 'all' || $discount->discountable_type == ("all-".$type)){
                return $discount;
            }
            //checking for single
            if($discount->discountable_type == $type){
                if($discount->discountable_id == $id){
                    if(ProductSeller::where('id' , $id)->join('store' , 'product_seller.store_id' , '=' , 'store.id')
                    ->where('store_type' , $type)->exists()){
                        return $discount;
                    }
                }
            }
            //checking for store
            if ($discount->discountable_type == "store") {
                    if (ProductSeller::where('product_seller.id' , $id)->join('store', 'product_seller.store_id', '=', 'store.id')
                    ->where('store_type', $type)->where('store.id' , $discount->discountable_id)->exists()) {
                        return $discount;
                    }
            }
            //checking for guild or category
            if($discount->discountable_type == "category"){
                if(ProductSeller::where('product_seller.id', $id)->join('category' , 'product_seller.category_id' , '=' , 'category.id')
                    ->join('store', 'product_seller.store_id', '=', 'store.id')
                    ->where('store_type', $type)
                    ->where('category.id' , $discount->discountable_id)
                    ->exists()){
                        return $discount;
                    }
            }
            if($discount->discountable_type == "guild"){
                if (ProductSeller::where('product_seller.id', $id)->join('category', 'product_seller.category_id', '=', 'category.id')
                    ->join('guild' , 'category.guild_id' , '=' , 'guild.id')
                    ->join('store', 'product_seller.store_id', '=', 'store.id')
                    ->where('store_type', $type)
                    ->where('guild.id', $discount->discountable_id)
                    ->exists()
                ) {
                    return $discount;
                }
            }
        }
        //for ad
        if($type == "ad"){
            if($discount->discountable_type == "all-ads" || $discount->discountable_type == "all"){
                return $discount;
            }
            if($discount->discountable_type == "ad"){
                if($discount->discountable_id == $id){
                    return $discount;
                }
            }
        }
        //for plan
        if ($type == "plan") {
            if ($discount->discountable_type == "all-plans" || $discount->discountable_type == "all") {
                return $discount;
            }
            if ($discount->discountable_type == "plan") {
                if ($discount->discountable_id == $id) {
                    return $discount;
                }
            }
        }
        //for upgrade
        if($type == "upgrade"){
            if ($discount->discountable_type == "all-upgrade" || $discount->discountable_type == "all") {
                return $discount;
            }
            if ($discount->discountable_type == "upgrade") {
                if ($discount->discountable_id == $id) {
                    return $discount;
                }
            }
        }
        //for sending
        if($type == "sending"){
            if($discount->discountable_type == "all-sending" || $discount->discountable_type == "all"){
                return $discount;
            }
            if($discount->discountable_type == "store-sending" || $discount->discountable_type == "product"){
                if($discount->discountable_id == $id){
                    return $discount;
                }
            }
        } 
        return null;
    }

}
