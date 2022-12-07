<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Libraries\Swal;
use App\Product_seller_attribute;
use App\ProductSeller;
use App\Store;
use Illuminate\Http\Request;

class ProductSellerAttributeController extends Controller
{
    public function index($store , $product_seller)
    {
        
        $productSeller = ProductSeller::find($product_seller);
        // $store = Store::find($product_seller->store_id);
        $productSellerAttributes =Product_seller_attribute::join('attribute' , 'attribute.id' , '=' , 'product_seller_attribute.attribute_id')
            ->join('product_seller', 'product_seller.id' , '=' , 'product_seller_attribute.product_seller_id')
            ->where('product_seller_id' , $product_seller)
            ->where('product_seller_attribute.deleted' , 0)
            ->select('product_seller_attribute.title' , 'product_seller_attribute.extra_price' , 'product_seller_attribute.id' , 'attribute.type' , 'attribute.id as attr_id' , 'product_seller.name')
            ->get();
        $store = Store::find($productSeller->store_id);
        $attributes = Attribute::where('store_type' , $store->store_type)->get();
        return view('admin.product_seller.attribute' , compact('productSellerAttributes' , 'productSeller' , 'attributes' , 'store'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'attribute_id' => 'required|exists:attribute,id',
            'title' => 'required|string',
            'extra_price' => 'required|numeric'
        ] , [
            'attribute_id.required' => 'نام ویژگی الزامی است',
            'attribute_id.in' => 'نام ویژگی نامعتبر است.',
            'title.required' => 'مشخصات ویژگی الزامی است.',
            'title.string' => 'مشخصات ویژگی نامعتبر است.',
            'extra_price.required' => 'قیمت افزایشی الزامی است.',
            'extra_price.numeric' => 'قیمت افزایشی نامعتبر است.',
        ]);
        Product_seller_attribute::create($request->all());
        return redirect()->back();
    }

    public function delete(Product_seller_attribute $attribute)
    {
        $attribute->deleted = 1;
        $attribute->save();
        Swal::success('حذف موفقیت آمیز', 'ویژگی مورد نظر با موفقیت حذف شد.');
        return redirect()->back();
    }

    public function userDelete(Product_seller_attribute $attribute)
    {
        $attribute->deleted = 1;
        $attribute->save();
        Swal::success('حذف موفقیت آمیز', 'ویژگی مورد نظر با موفقیت حذف شد.');
        return redirect()->back();
    }

    public function userAttributes(ProductSeller $product)
    {
        $attributes = $product->attributes()->where('deleted' , 0)->get();
        return view('frontend.my-account.products.attributes' , compact('attributes' , 'product'));
    }

    public function update(Request $request , Product_seller_attribute $attribute)
    {
        $request->validate([
            'attribute' => 'required|exists:attribute,id',
            'title' => 'required|string',
            'extra_price' => 'required|numeric'
        ] , [
            'attribute.required' => 'نام ویژگی الزامی است',
            'attribute.exists' => 'نام ویژگی نامعتبر است.',
            'title.required' => 'مشخصات ویژگی الزامی است.',
            'title.string' => 'مشخصات ویژگی نامعتبر است.',
            'extra_price.required' => 'قیمت افزایشی الزامی است.',
            'extra_price.numeric' => 'قیمت افزایشی نامعتبر است.',
        ]);
        $attribute->attribute_id = $request->attribute;
        $attribute->title = $request->title;
        $attribute->extra_price = $request->extra_price;
        $attribute->save();
        Swal::success('ویرایش موفقیت آمیز', 'ویژگی مورد نظر با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function store(Request $request , $product)
    {
        $request->validate([
           'type'  => 'required|in:exists:attribute,id' ,
           'name'  => 'required|string' ,
           'price' => 'required|numeric' ,
        ]);
        $attribute = new Product_seller_attribute();
        $attribute->product_seller_id = $product;
        $attribute->attribute_id      = $request->type;
        $attribute->title             = $request->name;
        $attribute->extra_price       = $request->price;
        $attribute->save();
        return response()->json([ "status" => 200] , 200);
    }

    public function edit(Request $request)
    {
        $request->validate([
           'id'    => 'required|exists:product_seller_attribute,id' ,
           'type'  => 'required|exists:attribute,id' ,
           'name'  => 'required|string' ,
           'price' => 'required|numeric' ,
        ]);

        $attribute = Product_seller_attribute::find($request->id);
        $attribute->attribute_id      = $request->type;
        $attribute->title             = $request->name;
        $attribute->extra_price       = $request->price;
        $attribute->save();
        return response()->json([ "status" => 200] , 200);
    }

    public function removeAttributeViaAjax(Request $request , Product_seller_attribute $attribute){
        $attribute->delete();
        return response()->json([
            'status' => 200,
            'message' => 'attribute_removed',
            'entire' => []
        ]);
    }
}
