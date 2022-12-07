<?php

namespace App\Http\Controllers\API;

use App\ProductPhoto;
use App\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class showAllApi extends Controller
{
    public function showAll($type , $offset , $limit)
    {
        if ($type == 'showNewest')
        {
            $product = new Products();
            $productQuery = $product->dbSelect(Products::FIELDS)
                ->where('product.deleted', '=', 0)
                ->where('product.is_vip', '=', 0)
                ->orderBy('product.created_at', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

            $newestProducts = collect($productQuery);

            foreach ($newestProducts as $index => $row) {
                $photos = ProductPhoto::where('product_id', $row->id)->get();
                foreach ($photos as $i => $photoItem) {
                    $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
                }
                $created_at_fa = jDate($row->created_at)->format('date');
                $newestProducts[$index]->created_at = $created_at_fa;
                $updated_at_fa = jDate($row->updated_at)->format('date');
                $newestProducts[$index]->updated_at = $updated_at_fa;
                $newestProducts[$index]->photos = $photos;
            }

            return response()->json(['newest_products' => $newestProducts], 200);
        }
        if ($type == 'showMostBuy')
        {
            $product = new Products();
            $productQuery = $product->dbSelect(Products::FIELDS)
                ->where('product.deleted', '=', 0)
                ->where('product.is_vip', '=', 0)
                ->orderBy('sale_price', 'desc')
                ->offset($offset)
                ->limit($limit)
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

            return response()->json(['most_purchased_products' => $MostBuyProducts], 200);
        }

        if ($type == '$showMostVisited')
        {
            $product = new Products();
            $productQuery = $product->dbSelect(Products::FIELDS)
                ->where('product.deleted', '=', 0)
                ->where('product.is_vip', '=', 0)
                ->orderBy('product.hits', 'desc')
                ->offset($offset)
                ->limit($limit)
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

            return response()->json(['most_visited_products' => $MostVisitedProducts], 200);
        }

        if ($type == '$showVip_products')
        {
            $product = new Products();
            $productQuery = $product->dbSelect(Products::FIELDS)
                ->where('product.deleted', '=', 0)
                ->where('product.is_vip', '=', 1)
                ->offset($offset)
                ->limit($limit)
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

            return response()->json(['vip_products' => $showVip], 200);
        }

    }

}
