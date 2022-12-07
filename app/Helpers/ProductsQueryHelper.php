<?php

namespace App\Helpers;

use App\ProductSeller;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ProductsQueryHelper {
    //newests
    private function newestProductSellers($province) : Builder{
       return ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('address', 'address.id', '=', 'store.address_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->leftJoin('upgrades' , 'product_seller.id' , '=' ,'upgradable_id')
        ->leftJoin('upgrade_positions' , 'upgrades.upgrade_position_id' , '=' , 'upgrade_positions.id')
        ->where(function($query){
            return $query->where('upgrades.upgradable_type', ProductSeller::class)->orWhereNull('upgrades.upgradable_type');
        })
        ->where(function ($query) {
            return $query->where('upgrades.status', 'approved')->orWhereNull('upgrades.status');
        })
            ->where(function ($activityTypeSubQuery) use ($province) {
                $activityTypeSubQuery->where('store.activity_type', 'country')
                    ->orWhere(function ($subWhere) use ($province) {
                        $subWhere->where('store.activity_type', 'province')
                            ->where('province.id', $province);
                    });
            })
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->where('product_seller.status', 'approved')
            ->where('product_seller.visible', 1)
            ->where(function($query){
                $query->where('product_seller.quantity' , '>' , 0)->orWhereNull('product_seller.quantity');
            })
            ->select('product_seller.id', 'product_seller.name', 'product_seller.price', 'product_seller.hint', 'product_seller.discount', 'store.activity_type' , 'product_seller.store_id' , 'product_seller.category_id' , DB::raw('MAX(upgrades.from_marketer) as marketer'))
            ->addSelect(DB::raw('(
                select round(product_seller.price - ((product_seller.price * product_seller.discount) / 100))
            ) as discountPrice'))
            ->orderBy(DB::raw("GREATEST(MAX(IFNULL(upgrades.created_at , '2000-01-01 00:00:00')) , MAX(product_seller.created_at))"), 'desc')
            ->groupBy('product_seller.id');

    }
    public function newestServicesQuery($province) : Collection{
       return $this->newestProductSellers($province)->where(function ($query){
            return $query->where('upgrade_positions.position', 'service_in_newest')->orWhereNull('upgrade_positions.position');
       })
       ->where('store.store_type','service')->take(10)->get();
    }
    public function newestProductsQuery($province): Collection{
        return $this->newestProductSellers($province)->where(function ($query) {
            return $query->where('upgrade_positions.position', 'product_in_newest')->orWhereNull('upgrade_positions.position');
        })->where('store.store_type' , 'product')->take(10)->get();
    }
    //highSales
    private function highSaleProductSellers($province) : Builder{
        return ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('address', 'address.id', '=', 'store.address_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->leftJoin('upgrades', 'product_seller.id', '=', 'upgradable_id')
        ->leftJoin('upgrade_positions', 'upgrades.upgrade_position_id', '=', 'upgrade_positions.id')
        ->where(function ($query) {
            return $query->where('upgrades.upgradable_type', ProductSeller::class)->orWhereNull('upgrades.upgradable_type');
        })
        ->where(function ($query) {
            return $query->where('upgrades.status', 'approved')->orWhereNull('upgrades.status');
        })
        ->where(function ($activityTypeSubQuery) use ($province) {
            $activityTypeSubQuery->where('store.activity_type', 'country')
            ->orWhere(function ($subWhere) use ($province) {
                $subWhere->where('store.activity_type', 'province')
                ->where('province.id', $province);
            });
        })
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->where('product_seller.status', 'approved')
            ->where('product_seller.visible', 1)
            ->where(function ($query) {
                $query->where('product_seller.quantity', '>', 0)->orWhereNull('product_seller.quantity');
            })
            ->select('product_seller.id', 'product_seller.name', 'product_seller.price', 'product_seller.hint', 'product_seller.discount', 'store.activity_type' ,'product_seller.store_id', 'product_seller.category_id' , DB::raw('MAX(upgrades.from_marketer) as marketer'))
            ->addSelect(DB::raw('(
                select round(product_seller.price - ((product_seller.price * product_seller.discount) / 100))
            ) as discountPrice'))
            ->addSelect(DB::raw('(
                select round(sum(((bill_item.price - ((bill_item.price * bill_item.discount) / 100))) * bill_item.quantity))  
                from bill_item 
                join bill ON bill.id = bill_item.bill_id 
                where bill_item.product_id = product_seller.id and (bill.status = "delivered" or bill.status = "approved")
            ) as sale_price'))
            ->orderBy(DB::raw('MAX(upgrades.id)'), 'desc')
            ->orderBy('sale_price', 'desc')
            ->groupBy('product_seller.id');

    }
    public function highSaleProducts($province , $offstet=0 , $limit=10) : Collection{
        return $this->highSaleProductSellers($province)->where(function ($query) {
            return $query->where('upgrade_positions.position', 'product_in_most_sold')->orWhereNull('upgrade_positions.position');
        })->where('store.store_type' , 'product')->offset($offstet)->limit($limit)->get();
    }
    public function highSaleServices($province , $offstet=0, $limit=10) : Collection{
        return $this->highSaleProductSellers($province)->where(function ($query) {
            return $query->where('upgrade_positions.position', 'service_in_most_sold')->orWhereNull('upgrade_positions.position');
        })->where('store.store_type', 'service')->offset($offstet)->limit($limit)->get();
    }
    private function highVisitedProductSellers($province) : Builder {
         return ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('address', 'address.id', '=', 'store.address_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->leftJoin('upgrades', 'product_seller.id', '=', 'upgradable_id')
        ->leftJoin('upgrade_positions', 'upgrades.upgrade_position_id', '=', 'upgrade_positions.id')
        ->where(function ($query) {
            return $query->where('upgrades.upgradable_type', ProductSeller::class)->orWhereNull('upgrades.upgradable_type');
        })
        ->where(function ($query) {
            return $query->where('upgrades.status', 'approved')->orWhereNull('upgrades.status');
        })
        ->where(function ($activityTypeSubQuery) use ($province) {
            $activityTypeSubQuery->where('store.activity_type', 'country')
                ->orWhere(function ($subWhere) use ($province) {
                    $subWhere->where('store.activity_type', 'province')
                    ->where('province.id', $province);
                });
        })
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->where('product_seller.status', 'approved')
            ->where('product_seller.visible', 1)
            ->where(function ($query) {
                $query->where('product_seller.quantity', '>', 0)->orWhereNull('product_seller.quantity');
            })
            ->select('product_seller.id', 'product_seller.name', 'product_seller.price', 'product_seller.hint', 'product_seller.discount', 'store.activity_type' ,'product_seller.store_id', 'product_seller.category_id' , DB::raw('MAX(upgrades.id) as upgrade_id') , DB::raw('MAX(upgrades.from_marketer) as marketer'))
            ->addSelect(DB::raw('(
                select round(product_seller.price - ((product_seller.price * product_seller.discount) / 100))
            ) as discountPrice'))
            ->orderBy(DB::raw('MAX(upgrades.id)'), 'desc')
            ->orderBy('product_seller.hint', 'desc')
            ->groupBy('product_seller.id');

    }
    public function highVisitedProducts($province) : Collection{
        return $this->highVisitedProductSellers($province)->where(function ($query) {
            return $query->where('upgrade_positions.position', 'product_in_most_visited')->orWhereNull('upgrade_positions.position');
        })->where('store.store_type' , 'product')
        ->take(10)->get();
    }
    public function highVisitedServices($province) : Collection {
        return $this->highVisitedProductSellers($province)->where(function ($query) {
            return $query->where('upgrade_positions.position', 'service_in_most_visited')->orWhereNull('upgrade_positions.position');
        })->where('store.store_type', 'service')
        ->take(10)->get();
    }
    private function hasDiscountProductSellers($province) : Builder{
        return ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('address', 'address.id', '=', 'store.address_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->where(function ($activityTypeSubQuery) use ($province) {
            $activityTypeSubQuery->where('store.activity_type', 'country')
                ->orWhere(function ($subWhere) use ($province) {
                    $subWhere->where('store.activity_type', 'province')
                    ->where('province.id', $province);
                });
        })
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->where('product_seller.status', 'approved')
            ->where('product_seller.visible', 1)
            ->where('product_seller.discount', '!=', 0)
            ->where(function ($query) {
                $query->where('product_seller.quantity', '>', 0)->orWhereNull('product_seller.quantity');
            })
            ->select('product_seller.id', 'product_seller.name', 'product_seller.price', 'product_seller.hint', 'product_seller.discount', 'store.activity_type' ,'product_seller.store_id', 'product_seller.category_id')
            ->addSelect(DB::raw('(
                select round(product_seller.price - ((product_seller.price * product_seller.discount) / 100))
            ) as discountPrice'))
            ->orderBy('product_seller.id', 'desc');
    }
    public function hasDiscountProducts($province) : Collection{
        return $this->hasDiscountProductSellers($province)
        ->where('store.store_type' , 'product')
        ->take(10)->get();
    }
    public function hasDiscountServices($province) : Collection
    {
        return $this->hasDiscountProductSellers($province)
            ->where('store.store_type', 'service')
            ->take(10)->get();
    }
    private function vipProductSellers($province) : Builder{
        return ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('address', 'address.id', '=', 'store.address_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->where(function ($activityTypeSubQuery) use ($province) {
            $activityTypeSubQuery->where('store.activity_type', 'country')
                ->orWhere(function ($subWhere) use ($province) {
                    $subWhere->where('store.activity_type', 'province')
                    ->where('province.id', $province);
                });
        })
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->where('product_seller.status', 'approved')
            ->where('product_seller.visible', 1)
            ->where('product_seller.is_vip', 1)
            ->where(function ($query) {
                $query->where('product_seller.quantity', '>', 0)->orWhereNull('product_seller.quantity');
            })
            ->select('product_seller.id', 'product_seller.name', 'product_seller.price', 'product_seller.hint', 'product_seller.discount', 'store.activity_type' ,'product_seller.store_id', 'product_seller.category_id' )
            ->addSelect(DB::raw('(
                select round(product_seller.price - ((product_seller.price * product_seller.discount) / 100))
            ) as discountPrice'))
            ->orderBy('product_seller.id', 'desc');
    }
    public function vipProducts($province) : Collection{
        return $this->vipProductSellers($province)->where('store.store_type' , 'product')->take(10)->get();
    }
    public function vipServices($province) : Collection{
        return $this->vipProductSellers($province)->where('store.store_type', 'service')->take(10)->get();
    }

    public function similarProducts($product){
        $similarProducts = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
            ->where('product_seller.category_id', $product->category_id)
            ->where('product_seller.id', '!=', $product->id)
            ->where('product_seller.visible', 1)
            ->where('product_seller.status', 'approved')
            ->where('store.store_type', $product->store->store_type)
            ->where(function ($query) {
                $query->where('product_seller.quantity', '>', 0)->orWhereNull('product_seller.quantity');
            })
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->select('product_seller.*')
            ->take(15)
            ->get();
        $similarProducts->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        return $similarProducts;
    }
    public function othersSeen($product){
        $othersSeen = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
            ->join('category' , 'product_seller.category_id' , '=' , 'category.id')
            ->where('category.guild_id', $product->category->guild_id)
            ->where('product_seller.id', '!=', $product->id)
            ->where('product_seller.visible', 1)
            ->where('product_seller.status', 'approved')
            ->where('store.store_type', $product->store->store_type)
            ->where(function ($query) {
                $query->where('product_seller.quantity', '>', 0)->orWhereNull('product_seller.quantity');
            })
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->select('product_seller.*')
            ->orderBy('product_seller.hint' , 'desc')
            ->take(15)
            ->get();
        $othersSeen->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        return $othersSeen;
    }
    public function productSuggestions($product){
        $similarProducts = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
            ->where('product_seller.category_id', $product->category_id)
            ->where('product_seller.id', '!=', $product->id)
            ->where('product_seller.visible', 1)
            ->where('product_seller.status', 'approved')
            ->where('is_vip' , 1)
            ->where('store.store_type', $product->store->store_type)
            ->where(function ($query) {
                $query->where('product_seller.quantity', '>', 0)->orWhereNull('product_seller.quantity');
            })
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->select('product_seller.*')
            ->take(15)
            ->get();
        $similarProducts->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        return $similarProducts;
    }

}

