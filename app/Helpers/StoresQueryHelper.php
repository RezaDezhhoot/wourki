<?php

namespace App\Helpers;

use App\Store;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class StoresQueryHelper
{
    public function topStores($province , $offset = 0 , $limit = 10) : Collection{
        return Store::join('address', 'address.id', '=', 'store.address_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->leftJoin('upgrades', 'store.id', '=', 'upgradable_id')
        ->leftJoin('upgrade_positions', 'upgrades.upgrade_position_id', '=', 'upgrade_positions.id')
        ->where(function ($query) {
            return $query->where('upgrades.upgradable_type', Store::class)->orWhereNull('upgrades.upgradable_type');
        })
        ->where(function ($query) {
            return $query->where('upgrade_positions.position', 'store_in_best')->orWhereNull('upgrade_positions.position');
        })
        ->where(function ($query) {
            return $query->where('upgrades.status' , 'approved')->orWhereNull('upgrades.status');
        })
        ->where(function ($activityTypeSubQuery) use ($province) {
            $activityTypeSubQuery->where('store.activity_type', 'country')
                ->orWhere(function ($subWhere) use ($province) {
                    $subWhere->where('store.activity_type', 'province')
                    ->where('province.id', $province);
                });
        })
            // ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.status', 'approved')
            ->where('store.visible', 1)
            ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.slug', 'store.user_name', 'store.activity_type')
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store.id = store_rate.store_id
            ) as rate'))
            ->orderBy(DB::raw('MAX(upgrades.id)'), 'desc')
            ->orderBy('rate', 'desc')
            ->groupBy('store.id')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
    public function lastStores($province , $offset = 0 , $limit = 8) : Collection{
        return
        Store::join('address', 'address.id', '=', 'store.address_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->leftJoin('upgrades', 'store.id', '=', 'upgradable_id')
        ->leftJoin('upgrade_positions', 'upgrades.upgrade_position_id', '=', 'upgrade_positions.id')
        ->where(function ($query){
            return $query->where('upgrades.upgradable_type', Store::class)->orWhereNull('upgrades.upgradable_type');
        })
        ->where(function ($query) {
            return $query->where('upgrade_positions.position', 'store_in_newest')->orWhereNull('upgrade_positions.position');
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
            // ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.status', '=', 'approved')
            ->where('store.visible', '=', 1)
            ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.slug', 'store.user_name', 'store.activity_type')
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store.id = store_rate.store_id
            ) as rate'))
            ->groupBy('store.id')
            ->orderBy(DB::raw("GREATEST(MAX(IFNULL(upgrades.created_at , '2000-01-01 00:00:00')) , MAX(store.created_at))"), 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
