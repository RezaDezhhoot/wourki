<?php

namespace App\Helpers;

use App\Discount;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class DiscountQueryHelper
{
    public function getAllDiscountsMadeByUsers(){
        return Discount::where('discountable_type' , 'store')
            ->where('admin_made' , false)
            ->whereDate('start_date' , '<=' , Carbon::now())
            ->whereDate('end_date' , '>=' , Carbon::now())
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get();
    }
    private function getProductSellerDiscountsMadeByUsers(){
        return Discount::join('store' , 'discountable_id' , '=' , 'store.id')
            ->where('discountable_type', 'store')
            ->where('admin_made', false)
            ->whereDate('start_date', '<=', Carbon::now())
            ->whereDate('end_date', '>=', Carbon::now())
            ->select('discounts.*')
            ->orderByDesc('updated_at')
            ->limit(8);
            
    }
    public function getProductDiscountsMadeByUsers()
    {
        return $this->getProductSellerDiscountsMadeByUsers()->where('store_type' , 'product')->get();
    }
    public function getServiceDiscountsMadeByUsers()
    {
        return $this->getProductSellerDiscountsMadeByUsers()->where('store_type', 'service')->get();
    }
}
