<?php

namespace App\Helpers;

use Carbon\Carbon;

class RawQueries{
    public static function hasSubscriptionForStore(){
            return '( (
                 select count(*) 
                 from seller_plan_subscription_details
                 where seller_plan_subscription_details.user_id = users.id and
                 seller_plan_subscription_details.to_date >="' . Carbon::now()->toDateString() . '" and
                 ((seller_plan_subscription_details.plan_type="store" and store.store_type<>"market") or (seller_plan_subscription_details.plan_type="market" and store.store_type="market"))            
            ) > 0 )';
    }
    public static function hasSubscriptionForProduct(){
        return '( (
                 select count(*) 
                 from seller_plan_subscription_details
                 where seller_plan_subscription_details.user_id = users.id and
                 seller_plan_subscription_details.to_date >="' . Carbon::now()->toDateString() . '" and
                 seller_plan_subscription_details.plan_type="store"           
            ) > 0 )';
    }
    public static function hasProductsForStore(){
        return '( (
                select count(*) 
                from product_seller
                 where product_seller.store_id = store.id and
                 product_seller.status = "approved" and
                 product_seller.visible = 1 and
                 (product_seller.quantity > 0 or product_seller.quantity IS NULL)
            ) > 0 )';
    }
}