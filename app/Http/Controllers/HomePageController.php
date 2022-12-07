<?php

namespace App\Http\Controllers;

use App\Ads;
use App\Helpers\AdsQueryHelper;
use App\Helpers\DiscountQueryHelper;
use App\Helpers\ProductsQueryHelper;
use App\Helpers\RawQueries;
use App\Helpers\StoresQueryHelper;
use App\ProductSeller;
use App\Slider;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;


class HomePageController extends Controller
{
    public function autoCompleteSearch(Request $request)
    {
        if (Cookie::has('province')) {
            $province = Cookie::get('province');
        } else {
            $province = 4;
        }
        $product = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
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
            ->where('product_seller.quantity', '!=', 0)
            ->where('product_seller.name', 'like', '%' . $request->search . '%')
            ->select('product_seller.id', 'product_seller.name')
            ->addSelect(DB::raw('(
                select round(product_seller.price - ((product_seller.price * product_seller.discount) / 100))
            ) as discountPrice'))
            ->orderBy('product_seller.id', 'desc')
            ->get();

        return response()->json($product, 200);
    }

    public function index(Request $request)
    {
        $sliders = Slider::where('type' , Slider::HOME)->get();
        foreach($sliders as $sIndex => $sRow){
            if($sRow->product_id !== null){
                $sliders[$sIndex]->link = route('show.product.seller' , $sRow->product_id);
            }else if($sRow->store_id !== null){
                $sliders[$sIndex]->link = route('show.store' , $sRow->store->user_name);
            }else{
                $sliders[$sIndex]->link = null;
            }
        }
        if (Cookie::has('province')) {
            $province = Cookie::get('province');
        } else {
            $province = 4;
        }
        $productsQueryHelper = new ProductsQueryHelper();
        $storesQueryHelper = new StoresQueryHelper();
        $adsQueryHelper = new AdsQueryHelper();
        $lastStoresCreate = $storesQueryHelper->lastStores($province);
        // $lastStoresCreate->each(function ($store) {
        //     $store->photo = optional($store->photos->first())->photo_name;
        // });

        $topStores = $storesQueryHelper->topStores($province);
        // $topStores->each(function ($store) {
        //     $store->photo = optional($store->photos->first())->photo_name;
        // });
        
        //vips
        $vipProducts = $productsQueryHelper->vipProducts($province);
        $vipServices = $productsQueryHelper->vipServices($province);
        $vipProducts->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        $vipServices->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        //discounts
        $hasDiscountProducts = $productsQueryHelper->hasDiscountProducts($province);
        $hasDiscountServices = $productsQueryHelper->hasDiscountServices($province);
        $hasDiscountProducts->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        $hasDiscountServices->each(function ($service) {
            $service->photo = optional($service->photos->first())->file_name;
        });
        //highSales
        $highSaleProducts = $productsQueryHelper->highSaleProducts($province);
        $highSaleServices = $productsQueryHelper->highSaleServices($province);
        $highSaleProducts->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        $highSaleServices->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        //highVisited
        $highVisitedProducts = $productsQueryHelper->highVisitedProducts($province);
        $highVisitedServices = $productsQueryHelper->highVisitedServices($province);
        $highVisitedProducts->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        $highVisitedServices->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });

        //newests
        $newProducts = $productsQueryHelper->newestProductsQuery($province);
        $newServices = $productsQueryHelper->newestServicesQuery($province);   
        $newServices->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });
        $newProducts->each(function ($product) {
            $product->photo = optional($product->photos->first())->file_name;
        });

        //ads
        $underBestStores = $adsQueryHelper->getAdsByPosition('under_best_stores');

        $underWourkiOffers = $adsQueryHelper->getAdsByPosition('under_wourki_offer');

        $underWourkiDiscount = $adsQueryHelper->getAdsByPosition('under_wourki_discount');
        $underLatestProducts = $adsQueryHelper->getAdsByPosition('under_latest_products');

        $underMostViewedProducts = $adsQueryHelper->getAdsByPosition('under_most_viewed_products');
        $discountsQueryHelper = new DiscountQueryHelper();
        $discounts = $discountsQueryHelper->getAllDiscountsMadeByUsers();

        return view('frontend/home/index', compact(
            'sliders'  , 'underBestStores' , 'underWourkiOffers' , 'underWourkiDiscount' , 'underLatestProducts' , 'underMostViewedProducts' , 'lastStoresCreate', 'topStores', 'vipProducts', 'highSaleProducts', 'highVisitedProducts', 'newProducts', 'hasDiscountProducts'
            , 'vipServices' , 'hasDiscountServices' , 'newServices' , 'highSaleServices' , 'highVisitedServices' , 'discounts'
        ));
    }
}
