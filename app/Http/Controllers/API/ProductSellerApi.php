<?php

namespace App\Http\Controllers\API;

use App\Address;
use App\Category;
use App\City;
use App\Discount;
use App\Guild;
use App\Helpers\ProductsQueryHelper;
use App\Helpers\RawQueries;
use App\Helpers\StoresQueryHelper;
use App\Http\Controllers\Controller;
use App\Product_seller_attribute;
use App\Product_seller_photo;
use App\ProductSeller;
use App\ProductSellerFavorite;
use App\Province;
use App\Rate;
use App\Slider;
use App\Store;
use App\Store_photo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Log;
use Throwable;

class ProductSellerApi extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'quantity' => 'numeric',
            'visible' => 'required|in:1,0',
            'category'    => 'required|exists:category,id',
            'store_id' => 'required|exists:store,id',
            // 'shipping_price_to_tehran' => $request->has('deliver_today_in_tehran') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            // 'shipping_price_to_other_towns' => $request->has('deliver_today_in_other_towns_check') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            // 'deliver_day_in_tehran' => $request->has('delivery_in_tehran_without_price') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            // 'deliver_day_in_other_towns' => $request->has('free_shipping_to_other_towns') ? 'nullable|numeric|min:0' : 'required|numeric|min:0'
        ],[
            'name.required'        => 'نام محصول الزامی است',
            'description.required' => 'توضیحات محصول الزامی است',
            'price.required'       => 'قیمت محصول الزامی است',
            'price.numeric'        => 'قیمت محصول نامعتبر است',
            'discount.numeric'     => 'تخفیف نامعتبر است',
            'quantity.required'    => 'موجودی انبار الزامی است',
            'quantity.numeric'     => 'موجودی انبار نامعتبر است',
            'category.required'    => 'دسته بندی الزامی است',
            'category.exists'      => 'دسته بندی نامعتبر است',
            // 'shipping_price_to_tehran.required' => 'هزینه حمل به تهران را وارد نمایید.',
            // 'shipping_price_to_other_towns.required' => 'هزینه حمل به شهرستان ها را وارد نمایید.',
            // 'deliver_day_in_tehran.required' => 'زمان ارسال به تهران را وارد نمایید.',
            // 'deliver_day_in_other_towns.required' => 'زمان ارسال به شهرستان ها را وارد نمایید.',
            // 'shipping_price_to_tehran.numeric' => 'هزینه حمل به تهران نامعتبر است.',
            // 'shipping_price_to_other_towns.numeric' => 'هزینه حمل به شهرستان ها نامعتبر است.',
            // 'deliver_day_in_tehran.numeric' => 'زمان ارسال به تهران نامعتبر است.',
            // 'deliver_day_in_other_towns.numeric' => 'زمان ارسال به شهرستان ها نامعتبر است.',
        ]);
        Log::info('before anything');
        if($validator->fails()){
            Log::info(json_encode($validator->errors()->all()));
            return response()->json(['status' => 400 , 'errors' => $validator->errors()->all()] , 200);
        }
        $product = new ProductSeller();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        if ($request->filled('discount'))
            $product->discount = $request->discount;
        else
            $product->discount = 0;
        if($product->store->store_type == 'product'){
            if(!$request->quantity){
                return response()->json(['status' => 400 , 'errors' => ['quantity']] , 400);
            }
            $product->quantity = $request->quantity;
        }
        else{
            $product->quantity = null;
        }
        $product->quantity = $request->quantity;
        $product->visible = $request->visible;
        $product->category_id = $request->category_id;
        $product->store_id = $request->store_id;
        $product->status = 'pending';
        $product->is_vip = 0;
        $product->guarantee_mark = 0;
        $product->deliver_time_in_tehran = 0;
        $product->deliver_time_in_other_towns = 0;
        $product->shipping_price_to_tehran = 0;
        $product->shipping_price_to_other_towns = 0;
        $success = null;
        Log::info('hello world');
        try{
        $success = $product->save();
        Log::info('result of saving product '.$success);
        }
        catch(Throwable $e){
            Log::info('error of saving product '. $e->getMessage());
        }
        if ($success) {
            if ($request->hasFile('photo_to_save')) {
                if(count($request->photo_to_save) == 0){
                    return response()->json(['status' => 400, 'errors' => ['photo_to_save is required']], 200);
                }
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
            }else{
                return response()->json(['status' => 400, 'errors' => ['photo_to_save is required']], 200);
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

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'discount' => 'numeric',
            'quantity' => 'numeric',
            'visible' => 'required|in:1,0',
            'category'    => 'required|exists:category,id',
            'store_id' => 'required|exists:store,id',
            // 'shipping_price_to_tehran' => $request->has('deliver_today_in_tehran') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            // 'shipping_price_to_other_towns' => $request->has('deliver_today_in_other_towns_check') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            // 'deliver_day_in_tehran' => $request->has('delivery_in_tehran_without_price') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            // 'deliver_day_in_other_towns' => $request->has('free_shipping_to_other_towns') ? 'nullable|numeric|min:0' : 'required|numeric|min:0'
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
            // 'shipping_price_to_tehran.required' => 'هزینه حمل به تهران را وارد نمایید.',
            // 'shipping_price_to_other_towns.required' => 'هزینه حمل به شهرستان ها را وارد نمایید.',
            // 'deliver_day_in_tehran.required' => 'زمان ارسال به تهران را وارد نمایید.',
            // 'deliver_day_in_other_towns.required' => 'زمان ارسال به شهرستان ها را وارد نمایید.',
            // 'shipping_price_to_tehran.numeric' => 'هزینه حمل به تهران نامعتبر است.',
            // 'shipping_price_to_other_towns.numeric' => 'هزینه حمل به شهرستان ها نامعتبر است.',
            // 'deliver_day_in_tehran.numeric' => 'زمان ارسال به تهران نامعتبر است.',
            // 'deliver_day_in_other_towns.numeric' => 'زمان ارسال به شهرستان ها نامعتبر است.',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $product = ProductSeller::find($request->id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        if ($request->filled('discount'))
            $product->discount = $request->discount;
        else
            $product->discount = 0;
        if ($product->store->store_type == 'product') {
            if (!$request->quantity) {
                return response()->json(['status' => 400, 'errors' => ['quantity']], 400);
            }
            $product->quantity = $request->quantity;
        } else {
            $product->quantity = null;
        }
        $product->visible = $request->visible;
        $product->category_id = $request->category_id;

        $product->guarantee_mark = 0;
        $product->deliver_time_in_tehran = 0;
        $product->deliver_time_in_other_towns = 0;
        $product->shipping_price_to_tehran = 0;
        $product->shipping_price_to_other_towns = 0;
        $product->status = 'pending';
        $product->is_vip = 0;
        $success = $product->save();

        if ($success) {
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

//                    $productPhotos = new Product_seller_photo();
//                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
//                    $productPhotos->file_name = $photoName;
//                    $productPhotos->seller_product_id = $product->id;
//                    $productPhotos->save();
//                    $photo->move(public_path('/image/product_seller_photo'), $photoName);
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
        }

        return response()->json(['status' => 200], 200);
    }

    public function delete(Request $request)
    {
        $productSeller = ProductSeller::find($request->id);
        $productSeller->status = 'deleted';
        $productSeller->save();
        return response()->json(['status' => 200], 200);
    }

    public function index(Request $request)
    {
        if($request->query('store_type') != 'product' && $request->query('store_type') != 'service' ){
            return response()->json(['error' => 'invalid store_type'] , 200);
        }
        $perPage = 10;
        $offset = ($request->page - 1) * $perPage;
        $limit = $perPage;
        $store_type = $request->query('store_type');
        $user = auth()->guard('api')->user();
        $store = Store::where('user_id', $user->id)->where('store_type', $store_type)->first();
        if($store){
        $productSeller = $store->products()
            ->where('status', '!=', 'deleted')
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->with(['attributes' => function ($query) {
                $query->select('*')
                    ->where('deleted', 0)
                    ->with(['attribute']);
            }]);

        $countProductSeller = $store->products()->where('status', '!=', 'deleted');
        if ($request->filled('visible')) {
            $productSeller->where('visible', $request->visible);
            $countProductSeller->where('visible', $request->visible);
        }

        $productSeller = $productSeller->get();
        $countProductSeller = count($countProductSeller->get());
        foreach ($productSeller as $index => $product) {
            foreach ($product->photos as $index => $photo) {
                $product->photos[$index]->photo_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
    }
    else{
        $productSeller = null;
        $countProductSeller = 0;
    }
        if ($request->filled('page')) {
            return response()->json([
                'productSeller' => $productSeller,
                'paginator' => [
                    'total' => ceil($countProductSeller / 10),
                    'perPage' => 10,
                    'currentPage' => $request->page,
                    'lastPage' => ceil($countProductSeller / 10),
                ]
            ], 200);
        } else {
            return response()->json(['productSeller' => $productSeller], 200);
        }

    }
    public function serviceIndex(Request $request)
    {
        $perPage = 10;
        $offset = ($request->page - 1) * $perPage;
        $limit = $perPage;
        $user = auth()->guard('api')->user();
        $store = Store::where('user_id', $user->id)->where('store_type', 'service')->first();
        if($store){
        $productSeller = $store->products()
            ->where('status', '!=', 'deleted')
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->with(['attributes' => function ($query) {
                $query->select('*')
                    ->where('deleted', 0)
                    ->with(['attribute']);
            }]);

        $countProductSeller = $store->products()->where('status', '!=', 'deleted');
        if ($request->filled('visible')) {
            $productSeller->where('visible', $request->visible);
            $countProductSeller->where('visible', $request->visible);
        }

        $productSeller = $productSeller->get();
        $countProductSeller = count($countProductSeller->get());

        foreach ($productSeller as $index => $product) {
            foreach ($product->photos as $index => $photo) {
                $product->photos[$index]->photo_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
    }
    else{
        $productSeller = null;
        $countProductSeller = 0;
    }

        if ($request->filled('page')) {
            return response()->json([
                'productSeller' => $productSeller,
                'paginator' => [
                    'total' => ceil($countProductSeller / 10),
                    'perPage' => 10,
                    'currentPage' => $request->page,
                    'lastPage' => ceil($countProductSeller / 10),
                ]
            ], 200);
        } else {
            return response()->json(['productSeller' => $productSeller], 200);
        }
    }
    public function indexWithoutPagination(Request $request)
    {
        $user = auth()->guard('api')->user();
        $store = Store::where('user_id', $user->id)->where('store_type', 'product')->first();
        if($store){
        $productSeller = optional($store)->products()
            ->where('status', '!=', 'deleted')
            ->latest()
            ->with(['attributes' => function ($query) {
                $query->select('*')
                    ->where('deleted', 0)
                    ->with(['attribute']);
            }]);

        // $countProductSeller = optional($store)->products()->where('status', '!=', 'deleted');
        if ($request->filled('visible')) {
            $productSeller->where('visible', $request->visible);
            // $countProductSeller->where('visible', $request->visible);
        }

        $productSeller = $productSeller->get();
        // $countProductSeller = count($countProductSeller->get());

        foreach ($productSeller as $index => $product) {
            foreach ($product->photos as $index => $photo) {
                $product->photos[$index]->photo_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
    }
    else{
        $productSeller = null;
    }
        if ($request->filled('page')) {
            return response()->json([
                'productSeller' => $productSeller,
            ], 200);
        } else {
            return response()->json(['productSeller' => $productSeller], 200);
        }
    }

    public function serviceIndexWithoutPagination(Request $request)
    {
        $user = auth()->guard('api')->user();
        $store = Store::where('user_id', $user->id)->where('store_type', 'service')->first();
        if($store){
        $productSeller = optional($store)->products()
            ->where('status', '!=', 'deleted')
            ->latest()
            ->with(['attributes' => function ($query) {
                $query->select('*')
                    ->where('deleted', 0)
                    ->with(['attribute']);
            }]);

        // $countProductSeller = optional($store)->products()->where('status', '!=', 'deleted');
        if ($request->filled('visible')) {
            $productSeller->where('visible', $request->visible);
            // $countProductSeller->where('visible', $request->visible);
        }

        $productSeller = $productSeller->get();
        // $countProductSeller = count($countProductSeller->get());

        foreach ($productSeller as $index => $product) {
            foreach ($product->photos as $index => $photo) {
                $product->photos[$index]->photo_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
    }
    else{
        $productSeller = null;
    }
        if ($request->filled('page')) {
            return response()->json([
                'productSeller' => $productSeller,
            ], 200);
        } else {
            return response()->json(['productSeller' => $productSeller], 200);
        }
    }

    public function highRateByStoreId(Request $request, Store $store)
    {
        $highRateStore = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->select('store.*' , 'users.thumbnail_photo')
            ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.visible', '=', 1)
            ->where('store.status', 'approved')
            ->with(['address' => function ($query) {
                $query->join('city', 'city.id', '=', 'address.city_id')
                    ->join('province', 'province.id', '=', 'city.province_id')
                    ->select('address.*', 'city.name as city_name', 'province.name as province_name');
            }])
            ->where('store.id', $store->id)
            ->first();
        if($highRateStore){
            $highRateStore->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $highRateStore->thumbnail_photo;
            // foreach ($highRateStore->photos as $indexx => $photo) {
            //     $highRateStore->photos[$indexx]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;
            // }
            $highRateStore->photos = [url()->to('/image/store_photos/') . '/' . $highRateStore->photo->photo_name];
            return response()->json([
                'status' => 200,
                'store' => $highRateStore
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'store' => null
            ]);
        }
    }
   

    public function all(Request $request)
    {
        $sliders = Slider::where('type' , Slider::HOME)->get();
        $sliders->each(function ($slider) {
            $slider->pic = url()->to('/image/slider/') . '/' . $slider->pic;
        });
        $highRateStore = Store::join('address', 'address.id', '=', 'store.address_id')
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
                return $query->where('upgrades.status', 'approved')->orWhereNull('upgrades.status');
            })
            ->where(function ($activityTypeSubQuery) use ($request) {
                $activityTypeSubQuery->where('store.activity_type', 'country')
                    ->orWhere(function ($subWhere) use ($request) {
                        $subWhere->where('store.activity_type', 'province')
                            ->where('province.id', $request->province_id);
                    });
            })
            ->select('store.*', 'users.thumbnail_photo')
            ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.visible', '=', 1)
            ->where('store.status', 'approved')
            ->with(['address' => function ($query) {
                $query->join('city', 'city.id', '=', 'address.city_id')
                    ->join('province', 'province.id', '=', 'city.province_id')
                    ->select('address.*', 'city.name as city_name', 'province.name as province_name');
            }])
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store_rate.store_id = store.id
            ) as rate'))
            ->orderBy('rate', 'desc')
            ->groupBy('store.id')
            ->orderBy(DB::raw('MAX(upgrades.updated_at)'), 'desc')
            ->take(20)
            ->get();
        $lastStores = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->leftJoin('upgrades', 'store.id', '=', 'upgradable_id')
            ->leftJoin('upgrade_positions', 'upgrades.upgrade_position_id', '=', 'upgrade_positions.id')
            ->where(function ($query) {
                return $query->where('upgrades.upgradable_type', Store::class)->orWhereNull('upgrades.upgradable_type');
            })
            ->where(function ($query) {
                return $query->where('upgrade_positions.position', 'store_in_newest')->orWhereNull('upgrade_positions.position');
            })
            ->where(function ($query) {
                return $query->where('upgrades.status', 'approved')->orWhereNull('upgrades.status');
            })
            ->where(function ($activityTypeSubQuery) use ($request) {
                $activityTypeSubQuery->where('store.activity_type', 'country')
                    ->orWhere(function ($subWhere) use ($request) {
                        $subWhere->where('store.activity_type', 'province')
                            ->where('province.id', $request->province_id);
                    });
            })
            ->select('store.*' , 'users.thumbnail_photo')
            ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.visible', '=', 1)
            ->where('store.status', 'approved')
            ->with(['address' => function ($query) {
                $query->join('city', 'city.id', '=', 'address.city_id')
                    ->join('province', 'province.id', '=', 'city.province_id')
                    ->select('address.*', 'city.name as city_name', 'province.name as province_name');
            }])
            ->groupBy('store.id')
            ->orderBy(DB::raw('MAX(upgrades.updated_at)'), 'desc')
            ->orderBy('store.created_at', 'desc')
            ->take(20)
            ->get();

        foreach ($highRateStore as $index => $store) {
            $store->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->thumbnail_photo;
            // foreach ($store->photos as $indexx => $photo) {
            //     $store->photos[$indexx]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;
            // }
            // $store->photos = [url()->to('/image/store_photos/') . '/' . optional($store->photo)->photo_name];
            $store->photo_url = url()->to('/image/store_photos/') . '/' . optional($store->photo)->photo_name;
            unset($store->photo);
        }
        foreach ($lastStores as $index => $store) {
            $store->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->thumbnail_photo;
            // foreach ($store->photos as $indexx => $photo) {
            //     $store->photos[$indexx]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;
            // }
            // $store->photos = [url()->to('/image/store_photos/') . '/' . optional($store->photo)->photo_name];
            $store->photo_url = url()->to('/image/store_photos/') . '/' . optional($store->photo)->photo_name;
            unset($store->photo);
        }

        $productQueryHelper = new ProductsQueryHelper();
        $vipProductSeller = $productQueryHelper->vipProducts($request->province_id);
        foreach ($vipProductSeller as $index => $product) {
            $vipProductSeller[$index]->storeName = $product->store->name;
            $vipProductSeller[$index]->category_name = $product->category->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
        $vipServices = $productQueryHelper->vipServices($request->province_id);
        foreach ($vipServices as $index => $product) {
            $vipServices[$index]->storeName = $product->store->name;
            $vipServices[$index]->category_name = $product->category->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
        $newProductSeller = $productQueryHelper->newestProductsQuery($request->province_id);
        $newServices = $productQueryHelper->newestServicesQuery($request->province_id);
        foreach ($newProductSeller as $index => $product) {
            $newProductSeller[$index]->category_name = $product->category->name;
            $newProductSeller[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
        foreach ($newServices as $index => $product) {
            $newServices[$index]->category_name = $product->category->name;
            $newServices[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }

        $hasDiscountProductSeller = $productQueryHelper->hasDiscountProducts($request->province_id);
        foreach ($hasDiscountProductSeller as $index => $product) {
            $hasDiscountProductSeller[$index]->category_name = $product->category->name;
            $hasDiscountProductSeller[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
        $hasDiscountServices = $productQueryHelper->hasDiscountServices($request->province_id);
        foreach ($hasDiscountServices as $index => $product) {
            $hasDiscountServices[$index]->category_name = $product->category->name;
            $hasDiscountServices[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }

        $highVisitedProductSeller = $productQueryHelper->highVisitedProducts($request->province_id);
        $highVisitedServices = $productQueryHelper->highVisitedServices($request->province_id);
        foreach ($highVisitedProductSeller as $index => $product) {
            $highVisitedProductSeller[$index]->category_name = $product->category->name;
            $highVisitedProductSeller[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
        foreach ($highVisitedServices as $index => $product) {
            $highVisitedServices[$index]->category_name = $product->category->name;
            $highVisitedServices[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }

        $highSaleProductSeller = $productQueryHelper->highSaleProducts($request->province_id);
        $highSaleServices = $productQueryHelper->highSaleServices($request->province_id);
        foreach ($highSaleProductSeller as $index => $product) {
            $highSaleProductSeller[$index]->category_name = $product->category->name;
            $highSaleProductSeller[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
        foreach ($highSaleServices as $index => $product) {
            $highSaleServices[$index]->category_name = $product->category->name;
            $highSaleServices[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }


        return response()->json([
            'sliders' => $sliders,
            'highRateStore' => $highRateStore,
            'vipProduct' => $vipProductSeller,
            'vipService' => $vipServices,
            'newProduct' => $newProductSeller,
            'newService' => $newServices,
            'hasDiscountProduct' => $hasDiscountProductSeller,
            'hasDiscountService' => $hasDiscountServices,
            'highVisitedProduct' => $highVisitedProductSeller,
            'highVisitedService' => $highVisitedServices,
            'highSaleProduct' => $highSaleProductSeller,
            'highSaleService' => $highSaleServices,
            'lastStores' => $lastStores,
            'allStores' => []
        ], 200);
    }

    public function highRatedStores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'nullable|numeric|min:0',
            'limit' => 'nullable|numeric|min:1',
            'province_id' => 'required|numeric|exists:province,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'validation_error_occurred',
                'entire' => [
                    'errors' => $validator->errors()->all()
                ]
            ]);
        }
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $highRateStore = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->where(function ($activityTypeSubQuery) use ($request) {
                $activityTypeSubQuery->where('store.activity_type', 'country')
                    ->orWhere(function ($subWhere) use ($request) {
                        $subWhere->where('store.activity_type', 'province')
                            ->where('province.id', $request->province_id);
                    });
            })
            ->select('store.*' , 'users.thumbnail_photo')
            ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.visible', '=', 1)
            ->where('store.status', 'approved')
            ->with(['address' => function ($query) {
                $query->join('city', 'city.id', '=', 'address.city_id')
                    ->join('province', 'province.id', '=', 'city.province_id')
                    ->select('address.*', 'city.name as city_name', 'province.name as province_name');
            }])
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store_rate.store_id = store.id
            ) as rate'))
            ->orderBy('rate', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($highRateStore as $index => $store) {
            if ($store->thumbnail_photo) {
                $highRateStore[$index]->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->thumbnail_photo;
            }
            // foreach ($store->photos as $indexx => $photo) {
            //     $store->photos[$indexx]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;
            // }
            $store->photo = $store->photo;
        }

        return response()->json([
            'status' => 200,
            'message' => 'high_rate_stores_returned',
            'entire' => [
                'stores' => $highRateStore
            ]
        ]);
    }

    public function latestStores(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'offset' => 'nullable|numeric|min:0',
            'limit' => 'nullable|numeric|min:1',
            'province_id' => 'required|numeric|exists:province,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'validation_error_occurred',
                'entire' => [
                    'errors' => $validator->errors()->all()
                ]
            ]);
        }
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $latestStore = Store::join('address', 'address.id', '=', 'store.address_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->where(function ($activityTypeSubQuery) use ($request) {
            $activityTypeSubQuery->where('store.activity_type', 'country')
            ->orWhere(function ($subWhere) use ($request) {
                $subWhere->where('store.activity_type', 'province')
                ->where('province.id', $request->province_id);
            });
        })
            ->select('store.*', 'users.thumbnail_photo')
            ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.visible', '=', 1)
            ->where('store.status', 'approved')
            ->with(['address' => function ($query) {
                $query->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->select('address.*', 'city.name as city_name', 'province.name as province_name');
            }])
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store_rate.store_id = store.id
            ) as rate'))
            ->latest('store.created_at')
            ->offset($offset)
            ->limit($limit)
            ->get();

        foreach ($latestStore as $index => $store) {
            if ($store->thumbnail_photo) {
                $latestStore[$index]->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->thumbnail_photo;
            }
            // foreach ($store->photos as $indexx => $photo) {
            //     $store->photos[$indexx]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;
            // }
            $store->photo = $store->photo;
        }

        return response()->json([
            'status' => 200,
            'message' => 'latest_stores_returned',
            'entire' => [
                'stores' => $latestStore
            ]
        ]);
    }
    public function favProduct(Request $request)
    {
        $user = auth()->guard('api')->user();
        $products = ProductSellerFavorite::join('product_seller', 'product_seller.id', 'product_seller_favorite.product_id')
            ->join('store', 'store.id', '=', 'product_seller.store_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('address', 'address.id', '=', 'store.address_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->where('product_seller.visible', 1)
            ->where('product_seller.status', 'approved')
            ->where('product_seller.quantity', '>', 0)
            ->where('store.status', 'approved')
            ->where('product_seller_favorite.user_id', $user->id)
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.visible', '=', 1);

        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;


        $products = $products->select('product_seller.*', 'store.id as store_id')
            ->offset($offset)
            ->limit($limit)
            ->get();

//        foreach ($products as $index => $product) {
//            $products[$index]->storeName = Store::where('id' , $product->store_id)->first()->name;
//            foreach ($product->photos as $indexx => $photo) {
//                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
//            }
//        }
        foreach ($products as $index => $product) {
            $products[$index]->storeName = Store::where('id', $product->store_id)->first()->name;
            $products[$index]->photos = Product_seller_photo::where('seller_product_id', $product->id)->get();
            try {
                foreach ($products[$index]->photos as $indexx => $photo) {
                    $products[$index]->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
                }
            } catch (\Exception $exception) {
                $product->photos = [];
            }
        }
        return response()->json($products, 200);
    }

    public function filterProducts(Request $request)
    {
        $products = ProductSeller::join('category', 'category.id', '=', 'product_seller.category_id')
            ->join('guild', 'guild.id', '=', 'category.guild_id')
            ->join('store', 'store.id', '=', 'product_seller.store_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('address', 'address.id', '=', 'store.address_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->where('product_seller.visible', 1)
            ->where('product_seller.status', 'approved')
            ->where('store.status', 'approved')
            ->where(function ($activityTypeSubQuery) use ($request) {
                $activityTypeSubQuery
                    ->where('store.activity_type', 'country')
                    ->orWhere(function ($subWhere) use ($request) {
                        $subWhere->where('store.activity_type', 'province')
                            ->where('province.id', $request->province_id);
                    });
            })
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.visible', '=', 1);

        if ($request->filled('category_id')) {
            $products->where('category.id', $request->category_id);
        }
        if ($request->filled('guild_id')) {
            $products->where('guild.id', $request->guild_id);
        }
        if ($request->filled('name')) {
            $products->where('product_seller.name', 'like', '%' . $request->name . '%');
        }
        if($request->store_type == 'service'){
            $products->where('store_type', 'service')
            ->whereNull('product_seller.quantity');
        }
        else{
            $products->where('store_type' , 'product')
            ->where('product_seller.quantity' , '>' , 0);
        }
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;


        $products = $products->select('product_seller.*', 'store_id')
            ->orderBy(DB::raw('RAND()'))
            ->offset($offset)
            ->limit($limit)
            ->get();
        $myProducts = null;
        if (auth()->guard('api')->check()) {
            $user = User::find(auth()->guard('api')->user()->id);
            if(!is_null($user->market))
            $myProducts = $user->market->products()->pluck('product_seller.id')->toArray();
        }
        foreach ($products as $index => $product) {
            $products[$index]->category_name = $product->category->name;
            $products[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
            $products[$index]->in_my_market = false;
            if($myProducts != null && array_search($product->id , $myProducts)){
                $products[$index]->in_my_market = true;
            }
        }
        return response()->json($products, 200);
    }

    public function filterProductsPaginated(Request $request)
    {
        $products = ProductSeller::join('category', 'category.id', '=', 'product_seller.category_id')
        ->join('guild', 'guild.id', '=', 'category.guild_id')
        ->join('store', 'store.id', '=', 'product_seller.store_id')
        ->join('users', 'users.id', '=', 'store.user_id')
        ->join('address', 'address.id', '=', 'store.address_id')
        ->join('city', 'city.id', '=', 'address.city_id')
        ->join('province', 'province.id', '=', 'city.province_id')
        ->where('product_seller.visible', 1)
        ->where('product_seller.status', 'approved')
        ->where('store.status', 'approved')
        ->where(function ($activityTypeSubQuery) use ($request) {
            $activityTypeSubQuery
                ->where('store.activity_type', 'country')
                ->orWhere(function ($subWhere) use ($request) {
                    $subWhere->where('store.activity_type', 'province')
                    ->where('province.id', $request->province_id);
                });
        })
            ->whereRaw(RawQueries::hasSubscriptionForProduct())
            ->where('store.visible', '=', 1);

        if ($request->filled('category_id')) {
            $products->where('category.id', $request->category_id);
        }
        if ($request->filled('guild_id')) {
            $products->where('guild.id', $request->guild_id);
        }
        if ($request->filled('name')) {
            $products->where('product_seller.name', 'like', '%' . $request->name . '%');
        }
        if ($request->store_type == 'service') {
            $products->where('store_type', 'service')
            ->whereNull('product_seller.quantity');
        } else {
            $products->where('store_type', 'product')
            ->where('product_seller.quantity', '>', 0);
        }
        $limit = $request->filled('limit') ? $request->limit : 10;
        $products = $products->select('product_seller.*', 'store_id')
        ->orderBy(DB::raw('RAND()'))
        ->simplePaginate($limit);
        $myProducts = null;
        if (auth()->guard('api')->check()) {
            $user = User::find(auth()->guard('api')->user()->id);
            if (!is_null($user->market))
                $myProducts = $user->market->products()->pluck('product_seller.id')->toArray();
        }
        foreach ($products as $index => $product) {
            $products[$index]->category_name = $product->category->name;
            $products[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
            $products[$index]->in_my_market = false;
            if ($myProducts != null && array_search($product->id, $myProducts)) {
                $products[$index]->in_my_market = true;
            }
        }
        return response()->json(['status' => 200 , 'data' => $products], 200);
    }


    public function productDetails(Request $request)
    {
        $user = auth()->guard('api')->user();
        $productDetails = ProductSeller::where('id', $request->id)
            ->where('visible', 1)
            ->where('status', 'approved')
            ->first();
        if(!$productDetails){
            return response()->json(['status' => 400] , 400);
        }
        $productDetails->hint = $productDetails->hint + 1;
        $productDetails->save();

        $productDetails->category_name = Category::where('id', $productDetails->category_id)->first()->name;

        if ($user) {
            $favCount = ProductSellerFavorite::where('user_id', $user->id)
                ->where('product_id', $productDetails->id)
                ->count();
            if ($favCount > 0)
                $productDetails->userFavProduct = 1;
            else
                $productDetails->userFavProduct = 0;
        } else
            $productDetails->userFavProduct = 0;

        $category = Category::where('id', $productDetails->category_id)->first();
        $attribute = Product_seller_attribute::join('attribute', 'attribute.id', '=', 'product_seller_attribute.attribute_id')
            ->where('product_seller_id', $productDetails->id)
            ->where('product_seller_attribute.deleted', '=', 0)
            ->select('product_seller_attribute.*', 'attribute.type')
            ->get();
        $guild = Guild::where('id', $category->guild_id)->first();
        $store = Store::where('id', $productDetails->store_id)->first();
        $store->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->user->thumbnail_photo;
        $store->address = Address::where('id', $store->address_id)->first();
        $store->address->city_name = City::where('id', $store->address->city_id)->first()->name;
        $store->address->province_id = City::where('id', $store->address->city_id)->first()->province_id;
        $store->address->province_name = Province::where('id', $store->address->province_id)->first()->name;
        $store->rate = Rate::where('store_id', $store->id)->avg('rate');

        $store->photos = Store_photo::where('store_id', $store->id)->get();
        foreach ($store->photos as $index => $photo)
            $store->photos[$index]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;

        foreach ($productDetails->photos as $index => $photo) {
            $productDetails->photos[$index]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
        }

        return response()->json([
            'product' => $productDetails,
            'category' => $category,
            'attribute' => $attribute,
            'guild' => $guild,
            'store' => $store,
        ], 200);
    }

    public function toggleFavProduct(Request $request)
    {
        $user = User::find(auth()->guard('api')->user()->id);
        $product = ProductSeller::find($request->id);
        $exists = ProductSellerFavorite::where('user_id', $user->id)
            ->where('product_id', $request->id)->exists();
        if ($exists) {
            $user->favoriteProducts()->detach($product);
            return response()->json(['status' => 201], 200);
        } else {
            $user->favoriteProducts()->attach($product);
            return response()->json(['status' => 200], 200);
        }
    }

    public function storeProducts(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $store = Store::find($request->store_id);
        if(!$store){
            return response()->json(['status' => 404] , 200);
        }

        $products = $store->products()
            ->where('status', 'approved')
            ->where('visible', 1);
        if ($request->filled('name'))
            $products->where('name', 'like', '%' . $request->name . '%');
        if ($request->filled('discount'))
            $products->where('discount', '>', 0);
        $products = $products
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();
        foreach ($products as $index => $product) {
            unset($product->pivot);
            $products[$index]->category_name = $product->category->name;
            $products[$index]->photos = Product_seller_photo::where('seller_product_id', $product->id)->get();
            foreach ($product->photos as $indexx => $photo)
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            $products[$index]->category = Category::where('id', $product->category_id)->first();
            $products[$index]->guild = Guild::where('id', $product->category->guild_id)->first();
        }
        return response()->json($products, 200);
    }
    public function storeProductsPaginated(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $store = Store::find($request->store_id);
        if (!$store) {
            return response()->json(['status' => 404], 200);
        }

        $products = $store->products()
            ->where('status', 'approved')
            ->where('visible', 1);
        if ($request->filled('name'))
        $products->where('name', 'like', '%' . $request->name . '%');
        if ($request->filled('discount'))
        $products->where('discount', '>', 0);
        $products = $products
            ->latest()
            ->skip($offset)
            ->take($limit)
            ->get();
        foreach ($products as $index => $product) {
            unset($product->pivot);
            $products[$index]->category_name = $product->category->name;
            $products[$index]->photos = Product_seller_photo::where('seller_product_id', $product->id)->get();
            foreach ($product->photos as $indexx => $photo)
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            $products[$index]->category = Category::where('id', $product->category_id)->first();
            $products[$index]->guild = Guild::where('id', $product->category->guild_id)->first();
        }
        $discounts = Discount::where('discountable_type' , 'store')->where('discountable_id' , $store->id)->where('admin_made' , false)->get();
        return response()->json(['status' => 200 , 'products' => $products , 'discounts' => $discounts], 200);
    }

    public function filterMainPage(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;

        if ($request->type == 'vip' || $request->type == 'vipServices') {
            $products = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
                ->join('address', 'address.id', '=', 'store.address_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->where('product_seller.is_vip', 1)
                ->where('product_seller.visible', 1)
                ->where('product_seller.status', 'approved')
                ->where(function ($query) {
                    $query->where('product_seller.quantity', '>', 0)
                        ->orWhereNull('product_seller.quantity');
                })
                ->where('store.store_type' , $request->type == 'vip' ? 'product' : 'service')
                ->where('store.visible', '=', 1)
                ->where('store.status', '=', 'approved')
                ->where(function ($activityTypeSubQuery) use ($request) {
                    $activityTypeSubQuery
                        ->where('activity_type', 'country')
                        ->orWhere(function ($subWhere) use ($request) {
                            $subWhere->where('store.activity_type', 'province')
                                ->where('province.id', $request->province_id);
                        });
                })
                ->select('product_seller.*')
                ->with(['attributes'])
                ->orderBy('product_seller.id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

        }
        if ($request->type == 'new' || $request->type == "newServices") {
            $products = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
                ->join('address', 'address.id', '=', 'store.address_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->where('store.status', '=', 'approved')
                ->where('store.visible', '=', 1)
                ->where('product_seller.status', 'approved')
                ->where('product_seller.visible', 1)
                ->where('store.store_type', $request->type == 'new' ? 'product' : 'service')
                ->where(function($query){
                    $query->where('product_seller.quantity', '>', 0)
                    ->orWhereNull('product_seller.quantity');
                })
                // ->where('product_seller.discount', 0)
                // ->where('product_seller.is_vip', 0)
                ->where(function ($activityTypeSubQuery) use ($request) {
                    $activityTypeSubQuery
                        ->where('activity_type', 'country')
                        ->orWhere(function ($subWhere) use ($request) {
                            $subWhere->where('store.activity_type', 'province')
                                ->where('province.id', $request->province_id);
                        });
                })
                ->orderBy('product_seller.id', 'desc')
                ->select('product_seller.*')
                ->with(['attributes'])
                ->orderBy('product_seller.id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

        }
        if ($request->type == 'discount' || $request->type == 'discountServices') {
            $products = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
                ->join('address', 'address.id', '=', 'store.address_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->where('store.visible', '=', 1)
                ->where('product_seller.discount', '>', 0)
                ->where('store.status', '=', 'approved')
                ->where('product_seller.status', 'approved')
                ->where('product_seller.visible', 1)
                ->where('product_seller.is_vip', 0)
                ->where('store.store_type', $request->type == 'discount' ? 'product' : 'service')
                ->where(function ($query) {
                    $query->where('product_seller.quantity', '>', 0)
                        ->orWhereNull('product_seller.quantity');
                })
                ->where(function ($activityTypeSubQuery) use ($request) {
                    $activityTypeSubQuery
                        ->where('activity_type', 'country')
                        ->orWhere(function ($subWhere) use ($request) {
                            $subWhere->where('store.activity_type', 'province')
                                ->where('province.id', $request->province_id);
                        });
                })
                ->select('product_seller.*')
                ->with(['attributes'])
                ->orderBy('product_seller.id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

        }
        if ($request->type == 'highVisit' || $request->type == 'highVisitServices') {
            $products = ProductSeller::join('store', 'store.id', '=', 'product_seller.store_id')
                ->join('address', 'address.id', '=', 'store.address_id')
                ->join('city', 'city.id', '=', 'address.city_id')
                ->join('province', 'province.id', '=', 'city.province_id')
                ->where('product_seller.visible', 1)
                ->where('product_seller.status', 'approved')
                ->where('store.store_type', $request->type == 'highVisit' ? 'product' : 'service')
                ->where(function ($query) {
                    $query->where('product_seller.quantity', '>', 0)
                        ->orWhereNull('product_seller.quantity');
                })
                ->where('store.visible', '=', 1)
                ->where('store.status', '=', 'approved')
                ->where(function ($activityTypeSubQuery) use ($request) {
                    $activityTypeSubQuery
                        ->where('activity_type', 'country')
                        ->orWhere(function ($subWhere) use ($request) {
                            $subWhere->where('store.activity_type', 'province')
                                ->where('province.id', $request->province_id);
                        });
                })
                ->orderBy('product_seller.hint', 'desc')
                ->select('product_seller.*')
                ->with(['attributes'])
                ->orderBy('product_seller.id', 'desc')
                ->offset($offset)
                ->limit($limit)
                ->get();

        }
        $stores = null;
        if($request->type == "lastStores"){
            $queryHelper = new StoresQueryHelper();
            $stores = $queryHelper->lastStores($request->province_id , $offset , $limit);
        }
        // if($request->type == "topStores"){
        //     $queryHelper = new StoresQueryHelper();
        //     $stores = $queryHelper->topStores($request->province_id , $offset, $limit);
        // }
        if($request->type == "highSale"){
            $queryHelper = new ProductsQueryHelper();
            $products = $queryHelper->highSaleProducts($request->province_id , $offset , $limit);
        }
        if($request->type == "highSaleServices"){
            $queryHelper = new ProductsQueryHelper();
            $products = $queryHelper->highSaleServices($request->province_id, $offset, $limit);
        }
        if(isset($products)){
        foreach ($products as $index => $product) {
            $products[$index]->storeName = $product->store->name;
            foreach ($product->photos as $indexx => $photo) {
                $product->photos[$indexx]->file_name = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            }
        }
    }

        return response()->json($stores ? $stores : $products, 200);
    }

    public function addRate(Request $request)
    {
        $user = auth()->guard('api')->user();
        $rate = Rate::where('user_id', $user->id)
            ->where('store_id', $request->store_id);
        if ($rate->exists()) {
            $rate = $rate->first();
            $rate->update(['rate' => $request->rate]);
        } else {
            Rate::create([
                'user_id' => $user->id,
                'store_id' => $request->store_id,
                'rate' => $request->rate,
            ]);
        }

        $avgStoreRate = Rate::where('store_id', $request->store_id)->avg('rate');
        return response()->json(['rate' => $avgStoreRate, 'status' => 200], 200);
    }
    public function othersSeenProducts($product_id){
        $product = ProductSeller::find($product_id);
        if(!$product){
            return response()->json(['status' => 404 , 'message' => 'محصول / خدمت مورد نظر یافت نشد'] , 200);
        }
        $queryHelper = new ProductsQueryHelper();
        $othersSeen = $queryHelper->othersSeen($product);
        foreach($othersSeen as $item){
            $item->photo_url = url()->to('image/product_seller_photo/350') . '/' . $item->photo;
            unset($item->photo);
        }
        return response()->json(['status' => 200 , 'data' => $othersSeen] , 200);

    }
    public function suggestionProducts($product_id){
        $product = ProductSeller::find($product_id);
        if (!$product) {
            return response()->json(['status' => 404, 'message' => 'محصول / خدمت مورد نظر یافت نشد'], 200);
        }
        $queryHelper = new ProductsQueryHelper();
        $suggestions = $queryHelper->productSuggestions($product);
        foreach ($suggestions as $item) {
            $item->photo_url = url()->to('image/product_seller_photo/350') . '/' . $item->photo;
            unset($item->photo);
        }
        return response()->json(['status' => 200, 'data' => $suggestions], 200);
    }

}
