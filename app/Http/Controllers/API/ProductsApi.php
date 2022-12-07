<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\Comment;
use App\_productSellerFavorite;
use App\ProductPhoto;
use App\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class ProductsApi extends Controller
{
    public function showAll(Request $request)
    {
        $type = $request->type;
        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : 1;
        $subcate = $request->subcategory;


        if ($type == 'showNewest' || ($type != 'showMostBuy' && $type != 'showMostVisited' && $type != 'showVip_products')) {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->where('product.subcatid', '=', $subcate)
                    ->orderBy('product.created_at', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->orderBy('product.created_at', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }


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

            return response()->json($newestProducts, 200);
        }
        if ($type == 'showMostBuy') {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->where('product.subcatid', '=', $subcate)
                    ->orderBy('sale_price', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->orderBy('sale_price', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }

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

            return response()->json($MostBuyProducts, 200);
        }

        if ($type == 'showMostVisited') {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->where('product.subcatid', '=', $subcate)
                    ->orderBy('product.hits', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->orderBy('product.hits', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }

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

            return response()->json($MostVisitedProducts, 200);
        }

        if ($type == 'showVip_products') {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 1)
                    ->where('product.subcatid', '=', $subcate)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 1)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }

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

            return response()->json($showVip, 200);
        }
    }

    public function showAllByCategory(Request $request)
    {
        $type = $request->type;
        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : 1;
        $subcate = $request->category;


        if ($type == 'showNewest' || ($type != 'showMostBuy' && $type != 'showMostVisited' && $type != 'showVip_products')) {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->where('category_id', '=', $subcate)
                    ->orderBy('product.created_at', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->orderBy('product.created_at', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }


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

            return response()->json($newestProducts, 200);
        }
        if ($type == 'showMostBuy') {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->where('category_id', '=', $subcate)
                    ->orderBy('sale_price', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->orderBy('sale_price', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }

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

            return response()->json($MostBuyProducts, 200);
        }

        if ($type == 'showMostVisited') {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->where('category_id', '=', $subcate)
                    ->orderBy('product.hits', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 0)
                    ->where('product.quantity', '>', 0)
                    ->orderBy('product.hits', 'desc')
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }

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

            return response()->json($MostVisitedProducts, 200);
        }

        if ($type == 'showVip_products') {
            $product = new Products();
            if (isset($subcate)) {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 1)
                    ->where('category_id', '=', $subcate)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            } else {
                $productQuery = $product->dbSelect(Products::FIELDS)
                    ->where('product.deleted', '=', 0)
                    ->where('product.is_vip', '=', 1)
                    ->offset($offset)
                    ->limit($limit)
                    ->get();
            }

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

            return response()->json($showVip, 200);
        }
    }

    public function similarProduct(Request $request)
    {
        $productId = $request->product_id;
        $subCatId = $request->subcat_id;

        $product = Products::where('id', $productId)->first();
        $product->hits = ($product->hits) + 1;
        $product->save();

        $user = auth()->guard('api')->user();
        if ($user) {
            $isFavP = $user->favoriteProducts()->where('product_id', $productId)->exists();
            $isFavP = $isFavP ? 1 : 0;
        } else {
            $isFavP = '0';
        }

        $productQuery = new Products();
        $productQuery = $productQuery->dbSelect(Products::FIELDS)
            ->where('product.subcatid', '=', $subCatId)
            ->where('product.deleted', '=', 0)
            ->where('product.quantity', '>', 0)
            ->where('product.id', '!=', $productId)
            ->take(10)
            ->get();

        $semiProduct = collect($productQuery);

        foreach ($semiProduct as $index => $row) {
            $photos = ProductPhoto::where('product_id', $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
            }
            $created_at_fa = jDate($row->created_at)->format('date');
            $semiProduct[$index]->created_at = $created_at_fa;
            $updated_at_fa = jDate($row->updated_at)->format('date');
            $semiProduct[$index]->updated_at = $updated_at_fa;
            $semiProduct[$index]->photos = $photos;
        }

        return response()->json(['is_fav' => $isFavP, 'similar_products' => $semiProduct], 200);

    }

    public function showById(Request $request)
    {
        $productId = $request->productId;

        $product = new Products();
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->where('product.deleted', '=', 0)
            ->where('product.id', '=', $productId)
            ->first();

        if ($productQuery) {
            $photos = ProductPhoto::where('product_id', $productQuery->id)->get();
            foreach ($photos as $index => $photo) {
                $photos[$index]->name = URL::to('/image/product_photos') . '/' . $photo->name;
            }
            $created_at_fa = jDate($productQuery->created_at)->format('date');
            $productQuery->created_at = $created_at_fa;
            $updated_at_fa = jDate($productQuery->updated_at)->format('date');
            $productQuery->updated_at = $updated_at_fa;
            $productQuery->photos = $photos;
            return response()->json($productQuery, 200);
        } else {
            return response()->json(['محصول مورد نظر یافت نشد'], 404);
        }
    }

    public function addToFav(Request $request)
    {
        $productId = $request->productId;
        $user = auth()->guard('api')->user();

        $favProduct = DB::table('fav_product')
            ->where('user_id', '=', $user->id)
            ->where('product_id', '=', $productId)
            ->get();

        if (count($favProduct) > 0) {
            $user->favoriteProducts()->detach($productId);
            return response()->json(['message' => 'yes'], 200);
        } else {
            $user->favoriteProducts()->attach($productId);
            return response()->json(['message' => 'no'], 200);
        }
    }

    public function comment(Request $request)
    {
        $productId = $request->productId;

        $comment = new Comment();
        $commentList = $comment->dbSelect(Comment::FIELDS)
            ->addSelect('response.comment as response_comment')
            ->where('comment.product_id', '=', $productId)
            ->where('product.deleted', '=', 0)
            ->where('comment.status', '=', 'approved')
            ->whereNotNull('comment.parent_comment_id')
            ->get();
        foreach ($commentList as $index => $row) {
            $commentList[$index]->created_at = jDate($row->created_at)->format('%d %B %Y');
            $commentList[$index]->updated_at = jDate($row->updated_at)->format('%d %B %Y');
        }
        if (count($commentList) > 0) {
            return response()->json($commentList, 200);
        } else
            return response()->json([ "status" => 200], 200);
    }

    public function commentCount(Request $request)
    {
        $productId = $request->productId;

        $comment = new Comment();
        $commentList = $comment->dbSelect(Comment::FIELDS)
            ->where('comment.product_id', '=', $productId)
            ->where('product.deleted', '=', 0)
            ->where('comment.status', '=', 'approved')
            ->get();

        $commentCount = count($commentList);
        if (count($commentList) > 0) {
            return response()->json(['commentCount' => $commentCount], 200);
        } else
            return response()->json(['commentCount' => 0], 200);
    }

    public function search(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;

        $product = new Products();
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->where('product.deleted', '=', 0)
            ->where('product.quantity', '>', 0)
            ->orderBy('product.created_at', 'desc')
            ->offset($offset)
            ->limit($limit);

        if ($request->filled('title')) {
            $productQuery = $productQuery->where('product.name', 'like', "%{$request->title}%");
        }
        if ($request->filled('subcat')) {
            $productQuery = $productQuery->where('product.subcatid', '=', $request->subcat);
        }
        if ($request->filled('costFrom') && $request->has('costTo')) {
            $productQuery = $productQuery->where('product.price', '>=', $request->costFrom)
                ->where('product.price', '<=', $request->costTo);
        }
        $productQuery = $productQuery->get();

        $searchProduct = collect($productQuery);

        foreach ($searchProduct as $index => $row) {
            $photos = ProductPhoto::where('product_id', $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
            }
            $created_at_fa = jDate($row->created_at)->format('date');
            $searchProduct[$index]->created_at = $created_at_fa;
            $updated_at_fa = jDate($row->updated_at)->format('date');
            $searchProduct[$index]->updated_at = $updated_at_fa;
            $searchProduct[$index]->photos = $photos;
        }

        return response()->json($searchProduct, 200);
    }

}
