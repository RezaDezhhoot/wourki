<?php

namespace App\Helpers;

use App\Ads;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

class AdsQueryHelper
{
    public function getAdsByPosition($position) : Collection{
        return Ads::join('ads_position', 'ads_position.id', '=', 'ads.ads_position_id')
        ->join('ads_stairs', 'ads_stairs.ads_id', 'ads.id')
        ->where('ads.pay_status', 'paid')
        ->where('ads.status', 'approved')
        ->where('ads.expire_date', '>=', Carbon::now()->toDateString())
        ->leftJoin('store', 'store.id', '=', 'ads.store_id')
        ->where(function ($storeQuery) {
            $storeQuery->whereNull('store.id')
            ->orWhere(function ($storeQuery2) {
                $storeQuery2->whereNotNull('store.id')
                ->where('store.visible', 1)
                    ->where('store.status', 'approved');
            });
        })
            ->select('ads.id', 'ads.pic', 'ads.final_pic', 'ads.link_type', 'ads.product_id', 'store.user_name', 'ads.store_id', 'store.slug')
            ->groupBy('ads.id', 'ads.final_pic', 'ads.link_type', 'ads.product_id', 'store.user_name', 'ads.store_id', 'store.slug', 'ordering_factor')
            ->where('ads_position.ads_position', $position)
            //            ->whereNotNull('ads.final_pic')
            ->addSelect(DB::raw('(
                select ads_stairs.created_at
                from ads_stairs
                where ads_id = ads.id
                limit 1
            ) as ordering_factor'))
            ->whereNotNull('ads.final_pic')
            ->orderByDesc('ads.updated_at')
            ->limit(10)
            ->get();
    }
}
