<?php

namespace App\Http\Controllers\API;

use App\ProductPhoto;
use App\Products;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;

class SliderApi extends Controller
{
    public function show()
    {
        $slider = Slider::all();

       foreach($slider as $index => $slide){
           $slider[$index]->name = \url()->to('/image/slider') . '/' . $slide->pic;
       }

        $product = new Products();
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->where('product.deleted', '=', 0)
            ->orderBy('product.created_at', 'desc')
            ->take(10)
            ->get();

        $newestProducts = collect($productQuery);

        foreach ($newestProducts as $index => $row) {
            $photos = ProductPhoto::where('product_id', $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
//                unset($photos[$i]->file_name);
            }
            $created_at_fa = jDate($row->created_at)->format('date');
            $newestProducts[$index]->created_at = $created_at_fa;
            $updated_at_fa = jDate($row->updated_at)->format('date');
            $newestProducts[$index]->updated_at = $updated_at_fa;
            $newestProducts[$index]->photos = $photos;
        }

        $product = new Products();
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->where('product.deleted', '=', 0)
            ->orderBy('sale_price', 'desc')
            ->take(10)
            ->get();
        $MostBuyProducts = collect($productQuery);

        foreach ($MostBuyProducts as $index => $row) {
            $photos = ProductPhoto::where('product_id', $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
            }
            $created_at_fa = jDate($row->created_at)->format('date');
            $MostBuyProducts[$index]->created_at = $created_at_fa;
            $updated_at_fa = jDate($row->updated_at)->format('date');
            $MostBuyProducts[$index]->updated_at = $updated_at_fa;
            $MostBuyProducts[$index]->photos = $photos;
        }

        $product = new Products();
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->where('product.deleted', '=', 0)
            ->orderBy('product.hits', 'desc')
            ->take(10)
            ->get();
        $MostVisitedProducts = collect($productQuery);

        foreach ($MostVisitedProducts as $index => $row) {
            $photos = ProductPhoto::where('product_id', $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
            }
            $created_at_fa = jDate($row->created_at)->format('date');
            $MostVisitedProducts[$index]->created_at = $created_at_fa;
            $updated_at_fa = jDate($row->updated_at)->format('date');
            $MostVisitedProducts[$index]->updated_at = $updated_at_fa;
            $MostVisitedProducts[$index]->photos = $photos;
        }


        $product = new Products();
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->where('product.deleted', '=', 0)
            ->where('product.is_vip', '=', 1)
            ->take(10)
            ->get();
        $showVip = collect($productQuery);

        foreach ($showVip as $index => $row) {
            $photos = ProductPhoto::where('product_id', $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
            }
            $created_at_fa = jDate($row->created_at)->format('date');
            $showVip[$index]->created_at = $created_at_fa;
            $updated_at_fa = jDate($row->updated_at)->format('date');
            $showVip[$index]->updated_at = $updated_at_fa;
            $showVip[$index]->photos = $photos;
        }

        return response()->json(
            ['slider' => $slider , 'newest_products' => $newestProducts,'most_purchased_products' =>  $MostBuyProducts, 'most_visited_products' =>  $MostVisitedProducts,  'vip_products' =>  $showVip]
            , 200
        );
    }
    public function getSliders(Request $request){
        $sliders = [];
        if($request->has('type')){
            $sliders = Slider::where('type' , $request->type)->get();
        }else{
            $sliders = Slider::all();
        }
        $sliders->each(function ($slider) {
            $slider->pic = url()->to('/image/slider/') . '/' . $slider->pic;
        });
        return response()->json(['status' => 200 , 'sliders' => $sliders] , 200);
    }
}
