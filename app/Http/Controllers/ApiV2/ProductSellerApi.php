<?php

namespace App\Http\Controllers\ApiV2;

use App\Product_seller_attribute;
use App\Product_seller_photo;
use App\ProductSeller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use DB;
use Intervention\Image\Facades\Image;
use Log;
use Throwable;
use Validator;

class ProductSellerApi extends Controller
{
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:product_seller,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'quantity' => 'numeric',
            'visible' => 'required|in:1,0',
            'category_id'    => 'required|exists:category,id',
            'guarantee_mark' => 'in:0,1',
            'shipping_price_to_tehran' => $request->has('deliver_today_in_tehran') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'shipping_price_to_other_towns' => $request->has('deliver_today_in_other_towns_check') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'deliver_day_in_tehran' => $request->has('delivery_in_tehran_without_price') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'deliver_day_in_other_towns' => $request->has('free_shipping_to_other_towns') ? 'nullable|numeric|min:0' : 'required|numeric|min:0'
        ], [
            'name.required'        => 'نام محصول الزامی است',
            'description.required' => 'توضیحات محصول الزامی است',
            'price.required'       => 'قیمت محصول الزامی است',
            'price.numeric'        => 'قیمت محصول نامعتبر است',
            'discount.numeric'     => 'تخفیف نامعتبر است',
            'quantity.required'    => 'موجودی انبار الزامی است',
            'quantity.numeric'     => 'موجودی انبار نامعتبر است',
            'category.required'    => 'دسته بندی الزامی است',
            'category.exists'      => 'دسته بندی نامعتبر است',
            'shipping_price_to_tehran.required' => 'هزینه حمل به تهران را وارد نمایید.',
            'shipping_price_to_other_towns.required' => 'هزینه حمل به شهرستان ها را وارد نمایید.',
            'deliver_day_in_tehran.required' => 'زمان ارسال به تهران را وارد نمایید.',
            'deliver_day_in_other_towns.required' => 'زمان ارسال به شهرستان ها را وارد نمایید.',
            'shipping_price_to_tehran.numeric' => 'هزینه حمل به تهران نامعتبر است.',
            'shipping_price_to_other_towns.numeric' => 'هزینه حمل به شهرستان ها نامعتبر است.',
            'deliver_day_in_tehran.numeric' => 'زمان ارسال به تهران نامعتبر است.',
            'deliver_day_in_other_towns.numeric' => 'زمان ارسال به شهرستان ها نامعتبر است.',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $product = ProductSeller::find($request->id);
        DB::beginTransaction();
        try{
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        if ($request->filled('discount'))
            $product->discount = $request->discount;
        else
            $product->discount = 0;
        if (Store::find($product->store_id)->store_type == 'product') {
            if (!$request->quantity) {
                return response()->json(['status' => 400, 'errors' => ['quantity']], 200);
            }
            $product->quantity = $request->quantity;
        } else {
            $product->quantity = null;
        }
        $product->visible = $request->visible;
        $product->category_id = $request->category_id;
        $product->guarantee_mark = $request->guarantee_mark ?? 0;
        $product->deliver_time_in_tehran = $request->deliver_day_in_tehran;
        $product->deliver_time_in_other_towns = $request->deliver_day_in_other_towns;
        $product->shipping_price_to_tehran = $request->shipping_price_to_tehran;
        $product->shipping_price_to_other_towns = $request->shipping_price_to_other_towns;
        $product->status = 'pending';
        $product->is_vip = 0;
        $product->save();
            if ($request->filled('photo_to_delete')) {
                foreach ($request->photo_to_delete as $photo) {
                    $productPhotos = Product_seller_photo::find($photo);
                    $productPhotos->delete();
                }
            }
            if ($request->hasFile('photo_to_save')) {
                foreach ($request->photo_to_save as $photo) {

                    $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('/image/product_seller_photo/'), $imageName);
                    Image::make(public_path('/image/product_seller_photo') . '/' . $imageName)
                        ->fit(350, 350, function ($constraint) {
                            $constraint->upsize();
                        })
                        ->save(public_path('/image/product_seller_photo/350') . '/' . $imageName);

                    $productPhoto = new Product_seller_photo();
                    $productPhoto->seller_product_id = $product->id;
                    $productPhoto->file_name = $imageName;
                    $productPhoto->save();

                }
            }
            if ($request->filled('attribute_to_delete')) {
                foreach ($request->attribute_to_delete as $attribute) {
                    $product_seller_attribute = Product_seller_attribute::find($attribute);
                    $product_seller_attribute->deleted = 1;
                    $product_seller_attribute->save();
                }
            }
            if ($request->filled('attribute_id') && $request->filled('title') && $request->filled('extra_price')) {
                for ($i = 0; $i < count($request->attribute_id); $i++) {
                    $product_seller_attribute = new Product_seller_attribute();
                    $product_seller_attribute->product_seller_id = $product->id;
                    $product_seller_attribute->attribute_id = $request->attribute_id[$i];
                    $product_seller_attribute->title = $request->title[$i];
                    $product_seller_attribute->extra_price = $request->extra_price[$i];
                    $product_seller_attribute->save();
                }
            }
            if ($request->filled('edit_attribute_id')) {
                for ($i = 0; $i < count($request->edit_attribute_id); $i++) {
                    $product_seller_attribute = Product_seller_attribute::where('id', $request->edit_product_attribute_id[$i])->first();
                    $product_seller_attribute->product_seller_id = $product->id;
                    $product_seller_attribute->attribute_id = $request->edit_attribute_id[$i];
                    $product_seller_attribute->title = $request->edit_title[$i];
                    $product_seller_attribute->extra_price = $request->edit_extra_price[$i];
                    $product_seller_attribute->save();
                }
            }
            DB::commit();
        return response()->json(['status' => 200], 200);
        }
        catch(Throwable $e){
            DB::rollBack();
            return response()->json(['status' => 400 , 'error' => $e->getMessage()] , 200);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'quantity' => 'numeric',
            'visible' => 'required|in:1,0',
            'category_id'    => 'required|exists:category,id',
            'store_id' => 'required|exists:store,id',
            'guarantee_mark' => 'in:0,1',
            'shipping_price_to_tehran' => $request->has('deliver_today_in_tehran') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'shipping_price_to_other_towns' => $request->has('deliver_today_in_other_towns_check') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'deliver_day_in_tehran' => $request->has('delivery_in_tehran_without_price') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'deliver_day_in_other_towns' => $request->has('free_shipping_to_other_towns') ? 'nullable|numeric|min:0' : 'required|numeric|min:0'
        ], [
            'name.required'        => 'نام محصول الزامی است',
            'description.required' => 'توضیحات محصول الزامی است',
            'price.required'       => 'قیمت محصول الزامی است',
            'price.numeric'        => 'قیمت محصول نامعتبر است',
            'discount.numeric'     => 'تخفیف نامعتبر است',
            'quantity.required'    => 'موجودی انبار الزامی است',
            'quantity.numeric'     => 'موجودی انبار نامعتبر است',
            'category.required'    => 'دسته بندی الزامی است',
            'category.exists'      => 'دسته بندی نامعتبر است',
            'shipping_price_to_tehran.required' => 'هزینه حمل به تهران را وارد نمایید.',
            'shipping_price_to_other_towns.required' => 'هزینه حمل به شهرستان ها را وارد نمایید.',
            'deliver_day_in_tehran.required' => 'زمان ارسال به تهران را وارد نمایید.',
            'deliver_day_in_other_towns.required' => 'زمان ارسال به شهرستان ها را وارد نمایید.',
            'shipping_price_to_tehran.numeric' => 'هزینه حمل به تهران نامعتبر است.',
            'shipping_price_to_other_towns.numeric' => 'هزینه حمل به شهرستان ها نامعتبر است.',
            'deliver_day_in_tehran.numeric' => 'زمان ارسال به تهران نامعتبر است.',
            'deliver_day_in_other_towns.numeric' => 'زمان ارسال به شهرستان ها نامعتبر است.',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $product = new ProductSeller();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        if ($request->filled('discount'))
            $product->discount = $request->discount;
        else
            $product->discount = 0;
        if (Store::find($request->store_id)->store_type == 'product') {
            if (!$request->quantity) {
                return response()->json(['status' => 400, 'errors' => ['quantity']], 400);
            }
            $product->quantity = $request->quantity;
        } else {
            $product->quantity = null;
        }
        $product->visible = $request->visible;
        $product->category_id = $request->category_id;
        $product->store_id = $request->store_id;
        $product->status = 'pending';
        $product->is_vip = 0;

        $product->guarantee_mark = $request->guarantee_mark ?? 0;
        $product->deliver_time_in_tehran = $request->deliver_day_in_tehran;
        $product->deliver_time_in_other_towns = $request->deliver_day_in_other_towns;
        $product->shipping_price_to_tehran = $request->shipping_price_to_tehran;
        $product->shipping_price_to_other_towns = $request->shipping_price_to_other_towns;

        $success = $product->save();

        if ($success) {
            if ($request->hasFile('photo_to_save')) {
                foreach ($request->photo_to_save as $photo) {

                    $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('/image/product_seller_photo'), $imageName);
                    Image::make(public_path('/image/product_seller_photo/') . '/' . $imageName)
                        ->save(public_path('/image/product_seller_photo/350') . '/' . $imageName);
//                    Image::make($photo)
//                        ->fit(1920, 1080, function ($constraint) {
//                            $constraint->upsize();
//                        })
//                        ->save(public_path('/image/product_seller_photo/') . '/' . $imageName);
//                    Image::make($photo)
//                        ->fit(350, 350, function ($constraint) {
//                            $constraint->upsize();
//                        })
//                        ->save(public_path('/image/product_seller_photo/350') . '/' . $imageName);

                    $productPhoto = new Product_seller_photo();
                    $productPhoto->seller_product_id = $product->id;
                    $productPhoto->file_name = $imageName;
                    $productPhoto->save();

//                    $productPhotos = new Product_seller_photo();
//                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
//                    $productPhotos->file_name = $photoName;
//                    $productPhotos->seller_product_id = $product->id;
//                    $photo->move(public_path('/image/product_seller_photo'), $photoName);
//                    $productPhotos->save();
                }
            }
            if ($request->filled('attribute_id') && $request->filled('title') && $request->filled('extra_price')) {
                for ($i = 0; $i < count($request->attribute_id); $i++) {
                    $product_seller_attribute = new Product_seller_attribute();
                    $product_seller_attribute->product_seller_id = $product->id;
                    $product_seller_attribute->attribute_id = $request->attribute_id[$i];
                    $product_seller_attribute->title = $request->title[$i];
                    $product_seller_attribute->extra_price = $request->extra_price[$i];
                    $product_seller_attribute->save();
                }
            }
        }

        return response()->json(['status' => 200], 200);
    }
}
