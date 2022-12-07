<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Category;
use App\Http\Requests\web\filterProductSellerRequest;
use App\ProductSeller;
use App\Store;
use App\UpgradePosition;
use Illuminate\Http\Request;

class AllProductSellerController extends Controller
{
    public function listAll(filterProductSellerRequest $request)
    {
//        dd($request->all());
        $stores = Store::where('status', 'approved')
            ->select('name', 'id')
            ->get();
        $allProduct = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('category', 'category.id', '=', 'product_seller.category_id')
            ->select('store.name as store_name', 'product_seller.name as product_name', 'guild.name as guild_name', 'product_seller.price', 'product_seller.discount', 'product_seller.status',
                'category.name as category_name', 'product_seller.description', 'product_seller.category_id',
                'product_seller.created_at', 'product_seller.quantity', 'product_seller.id', 'product_seller.visible', 'product_seller.is_vip',
                'product_seller.shipping_price_to_tehran' , 'product_seller.shipping_price_to_other_towns' , 'product_seller.deliver_time_in_tehran' ,
                'product_seller.deliver_time_in_other_towns')
            ->orderBy('product_seller.id', 'desc')
            ->with(['attributes' => function ($query) {
                $query->select('product_seller_attribute.*', 'attribute.type as attribute_type')
                    ->join('attribute', 'attribute.id', '=', 'product_seller_attribute.attribute_id');
            } , 'photos']);
        if ($request->query('store_type') == 'product'){
            $allProduct->where('store.store_type' , 'product');
        }
        if( $request->query('store_type') == 'service'){
            $allProduct->where('store.store_type' , 'service');
        }
        if ($request->filled('store')) {
            $allProduct->where('store.id', $request->store);
        }
        if ($request->filled('key_word')) {
            $allProduct->where('product_seller.name', 'like', '%' . $request->key_word . '%')
                ->orWhere('product_seller.description', 'like', '%' . $request->key_word . '%');
        }
        if ($request->filled('price_from') && $request->filled('price_to')) {
            $allProduct->where('product_seller.price', '>=', $request->price_from)
                ->where('product_seller.price', '<=', $request->price_to);
        }
        if ($request->filled('visibility') && $request->visibility == 1) {
            $allProduct->where('product_seller.visible', 1);
        } elseif ($request->filled('visibility') && $request->visibility == 0) {
            $allProduct->where('product_seller.visible', 0);
        }
        if ($request->filled('status') && $request->status == 'approved') {
            $allProduct->where('product_seller.status', 'approved');
        } elseif ($request->filled('status') && $request->status == 'rejected') {
            $allProduct->where('product_seller.status', 'rejected');
        } elseif ($request->filled('status') && $request->status == 'pending') {
            $allProduct->where('product_seller.status', 'pending');
        }
        if ($request->filled('exists') && $request->exists == 1) {
            $allProduct->where('product_seller.quantity', '>', 0);
        } elseif ($request->filled('exists') && $request->exists == 0) {
            $allProduct->where('product_seller.quantity', '=', 0);
        }
        if ($request->filled('user_full_name')) {
            $allProduct->where(function ($query) use ($request) {
                $query->where('users.first_name', 'like', "%" . $request->user_full_name . "%")
                    ->orWhere('users.last_name', 'like', "%" . $request->user_full_name . "%");
            });
        }
        if ($request->has('vip_products')) {
            $allProduct->where('product_seller.is_vip', '=', 1);
        }
        if ($request->filled('user_mobile')) {
            $allProduct->where('users.mobile', 'like', "%" . $request->user_mobile . "%");
        }
        $allProduct = $allProduct->paginate(15);
        $productCategories = Category::all();
        $attributes = Attribute::all();
        foreach ($allProduct as $index => $row) {
            $products = ProductSeller::find($row->id);
            $allProduct[$index]->store_name = $products->store->slug;
        }
//        dd($allProduct->total());
        $storeType = $request->query('store_type');
        $positions = UpgradePosition::all();
        return view('admin.product_seller.allProducts', compact('allProduct', 'attributes', 'stores', 'productCategories' , 'storeType' , 'positions'));
    }

    public function show_product($product)
    {
        $product = ProductSeller::where('id', $product)->first();
        $product->visible = 1;
        $product->save();
        return back();
    }

    public function hide_product($product)
    {
        $product = ProductSeller::where('id', $product)->first();
        $product->visible = 0;
        $product->save();
        return back();
    }

    public function approved_product($product)
    {
        ProductSeller::where('id', $product)->update(['status' => 'approved']);
        return redirect()->back();
    }

    public function pending_product($product)
    {
        ProductSeller::where('id', $product)->update(['status' => 'pending']);
        return redirect()->back();
    }

    public function reject_product($product)
    {
        ProductSeller::where('id', $product)->update(['status' => 'rejected']);
        return redirect()->back();
    }

    public function setVip(ProductSeller $product)
    {
        $product->is_vip = 1;
        $product->save();
        return back();
    }

    public function unSetVip(ProductSeller $product)
    {
        $product->is_vip = 0;
        $product->save();
        return back();
    }
}
