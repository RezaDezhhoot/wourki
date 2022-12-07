<?php

namespace App\Http\Controllers\API;

use App\Helpers\RawQueries;
use App\Http\Controllers\Controller;
use App\MarketCommission;
use App\MarketProduct;
use App\Product_seller_photo;
use App\ProductSeller;
use App\Store;
use App\Store_photo;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;
use Route;
use Validator;

class StoreApi extends Controller
{
    public function checkUsername(Request $request)
    {
        $user_name = $request->user_name;
        $userNameCount = Store::where('user_name', $user_name)->count();
        if ($userNameCount > 0) {
            return response()->json(['status' => 400], 400);
        } else
            return response()->json(['status' => 200], 200);
    }

    public function store(Request $request)
    {
        $validated = Validator::make($request->all() , [
            'slogan'                      => 'required|string|max:30',
            'address_id'                     => 'required|string|exists:address,id' ,
            'guild_id'                       => 'required|exists:guild,id' ,
            'name'                  => 'required|string' ,
            'user_name'                    => 'required|string|alpha_dash',
            'min_pay'                     => 'nullable|numeric' ,
            'about'                       => 'required|string' ,
            'phone_number'            => 'required|numeric' ,
            'activity_type'               => 'required|in:country,province',
            'image'                       => 'nullable|file|image'
        ]);
        if($validated->fails()){
            return response()->json(['status' => 400 , 'errors' => $validated->errors()->all()], 200);
        }
        try{
        $store_type = Route::currentRouteName() == 'storeProductStoreApi' ? 'product' : (Route::currentRouteName() == 'storeMarketStoreApi' ? 'market' : 'service');
        $user = auth()->guard('api')->user();
        $store = Store::where('user_id' , $user->id)->where('store_type' , $store_type)->first();
        Log::info('log from updating store. found store:');
        Log::info(json_encode($store));
        if ($store) {
            $isNewStore = false;
        } else {
            $exists = Store::where('user_name' , $request->user_name)->exists();
            if($exists)
                return response()->json(['status' => 400 , 'errors' => ['این نام کاربری از قبل موجود است']] , 200);
            $store = new Store();
            $isNewStore = true;
        }
        $store->user_id = $user->id;
        $store->address_id = $request->address_id;
        $store->slogan = $request->slogan;
        $store->status = 'pending';
        if($request->guild_id)
            $store->guild_id = $request->guild_id;
        $store->name = $request->name;
        $store->user_name = $request->user_name;
        if ($request->filled('min_pay'))
            $store->min_pay = $request->min_pay;
        $store->about = $request->about;
        $store->phone_number = $request->phone_number;
        $store->visible = $request->visible ? true : false;
        $photo = $request->file('image');
        $photos = Store_photo::where('store_id' , $store->id)->get();
        if($photo)
        foreach ($photos as $_photo) {
            if(File::exists(public_path(DIRECTORY_SEPARATOR .'image'.DIRECTORY_SEPARATOR.'store_photos'.DIRECTORY_SEPARATOR). $_photo->photoname)){
                File::delete(public_path(DIRECTORY_SEPARATOR . 'image' . DIRECTORY_SEPARATOR . 'store_photos' . DIRECTORY_SEPARATOR) . $_photo->photoname);
            }
            $_photo->delete();
        }
        $store->phone_number_visibility = $request->phone_number_visibility ? true : false;
        $store->mobile_visibility = $request->mobile_visibility ? true : false;
        $store->store_type = $store_type;
        $store->pay_type = 'both';
        $store->activity_type = $request->activity_type;
        $success = $store->save();
        if ($success) {
                if ($photo) {
                    $photoObj = new Store_photo();
                    $photoObj->store_id = $store->id;
                    $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('/image/store_photos'), $photoName);
                    $photoObj->photo_name = $photoName;
                    $photoObj->save();
                }
        }
        if($isNewStore){
            $store->gift_assigned_to_refferer = false;
            $store->save();
        }
        return response()->json(['status' => 200], 200);
    }
    catch(Exception $e){
        return response()->json(['error' => $e->getMessage()] , 200);
    }
    }

    public function filterStore(Request $request)
    {
        $stores = Store::join('product_seller', 'product_seller.store_id', '=', 'store.id')
            ->join('users' , 'users.id' , '=' , 'store.user_id')
            ->groupBy('store.id')
            ->join('address', 'address.id', '=', 'store.address_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->where('store.status', 'approved')
            ->where('store.visible', '=', 1)
            ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->select('store.*')
            ->orderBy(DB::raw('RAND()'))
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store_rate.store_id = store.id
            ) as rate'));
        if($request->filled('username')){
            $stores = $stores->where('store.user_name' , '=' , $request->username)
                ->get();
            if(count($stores) > 0 ){
                foreach ($stores as $store) {
                    $store->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->thumbnail_photo;
                    $store->photo = $store->photo;
                }
            }

        }else{
            $stores->where(function ($activityTypeQuery) use ($request) {
                $activityTypeQuery->where('store.activity_type', '=', 'country')
                    ->orWhere(function ($activityTypeProvince) use ($request) {
                        $activityTypeProvince->where('province.id', '=', $request->province_id)
                            ->where('store.activity_type', '=', 'province');
                    });
            });
            if ($request->filled('guild_id')) {
                $stores->where('store.guild_id', $request->guild_id);
            }
            if ($request->filled('name')) {
                $stores->where('store.name' , 'like' , '%' . $request->name . '%');
            }
            if ($request->filled('category_id')) {
                $stores->where('product_seller.category_id', $request->category_id);
            }
            if ($request->filled('offset') && $request->filled('limit')) {
                $offset = $request->offset;
                $limit = $request->limit;
            } else {
                $offset = 0;
                $limit = 1;
            }
            $stores = $stores->offset($offset)->limit($limit);
            $stores = $stores
                ->with(['address' => function ($query) {
                    $query->join('city', 'city.id', '=', 'address.city_id')
                        ->join('province', 'province.id', '=', 'city.province_id')
                        ->select('address.*', 'city.name as city_name', 'province.name as province_name');
                }])
                ->get();


            foreach ($stores as $store) {
                $store->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->thumbnail_photo;
                // foreach ($store->photos as $index => $photo) {
                //     $store->photos[$index]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;
                // }
                // $store->photos = $store->photo ? [url()->to('/image/store_photos/') . '/' . $store->photo] : [];
                $store->photo = $store->photo;
            }


        }


        return response()->json($stores, 200);
    }

    public function increaseHits(Request $request)
    {
        $store = Store::find($request->id);
        $store->total_hits +=1;
        $store->save();

        return response()->json(['status' => 200] , 200);
    }

    public function updateShabaCode(Request $request)
    {
        $store = Store::where('user_id' , auth()->guard('api')->user()->id)->first();
        $store->shaba_code = $request->shaba;
        $store->save();
        return response()->json(['status' => 200]);
    }
    public function getSingle(Request $request,$id){
        $store = Store::find($id);
        if(!$store){
            return response()->json(['status' => 404 , 'error' => 'Not Found!'] , 200);
        }
        unset($store->user_id);
        unset($store->shaba_code);
        unset($store->guild_id);
        unset($store->created_at);
        unset($store->updated_at);
        unset($store->visible);
        unset($store->status);
        unset($store->notified_finishing_subscription_plan);
        $store->photo_url = optional($store->photo)->photo_name;
        if($store->photo_url)
        $store->photo_url = url()->to('image/store_photos') . '/' . $store->photo_url;
        else
        $store->photo_url = null;
        return response()->json(['status' => 200 , 'store' => $store] , 200);
    }
    public function addProductToMarket(Request $request)
    {
        $validated = Validator::make($request->all() , [
            'product_ids' => ['required' , 'array'],
            'product_ids.*' => ['integer']
        ]);
        if($validated->fails()){
            return response()->json(['status' => 400 , 'errors' => $validated->errors()->all()] , 200);
        }
        $market = Store::where('user_id', auth()->guard('api')->user()->id)
            ->where('store_type', 'market')
            ->first();
        $products = ProductSeller::whereIn('id' , $request->product_ids)->get();
        if (!$market) {
            return response()->json(['status' => 400 , 'message' => 'لطفا ابتدا فروشگاه بازاریابی خود را وارد کنید'] , 200);
        }
        foreach($products as $product){
        if ($product->store->user_id == auth()->guard('api')->user()->id) {
            continue;
        }
        if (!$market->products()->where('product_seller.id', $product->id)->exists())
            $market->products()->attach($product);
        }
        return response()->json(['status' => 200, 'message' => 'با موفقیت انجام شد'], 200);

    }
    public function deleteProductFromMarket(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['integer']
        ]);
        if ($validated->fails()) {
            return response()->json(['status' => 400, 'errors' => $validated->errors()->all()], 200);
        }
        $market = Store::where('user_id', auth()->guard('api')->user()->id)->where('store_type', 'market')->first();
        MarketProduct::where('market_id', $market->id)->whereIn('product_id', $request->product_ids)->delete();
        return response()->json([ "status" => 200] , 200);
    }
    public function marketProducts()
    {
        $userStore = Store::where('user_id', auth()->guard('api')->user()->id)
            ->where('store_type', 'market')
            ->first();
        $products = $userStore->products;
        foreach ($products as $index => $product) {
            $photo = Product_seller_photo::where('seller_product_id', $product->id)->first();
            if($photo)
            $products[$index]->photo_url = url()->to('/image/product_seller_photo/') . '/' . $photo->file_name;
            else {
                $products[$index]->photo_url = null;
            }
            $product->invite_link = url()->to('product/' . $product->id) . '?code=' . $userStore->id;
            $commission = MarketCommission::where('category_id', $product->category_id)->first();
            $product->commission = $commission ? $commission->amount : 0;
            unset($product->pivot);
        }
        unset($userStore->products);
        return response()->json(['status' => 200 , 'userStore' => $userStore , 'productSeller' => $products , ] , 200);
    }
    public function getMyMarket(){
        $userStore = Store::where('user_id', auth()->guard('api')->user()->id)
            ->where('store_type', 'market')
            ->first();
        return response()->json(['status' => 200, 'market' => $userStore], 200);
    }
    public function allStores(){
        $stores = Store::join('users', 'users.id', '=', 'store.user_id')
        ->where('status' , 'approved')
        ->where('visible' , 1)
        ->whereRaw(RawQueries::hasSubscriptionForStore())
        ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.slug', 'store.user_name', 'store.activity_type', 'store.created_at')
        ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store.id = store_rate.store_id
            ) as rate'))
        ->paginate(20);
        return response()->json(['status' => 200 , 'stores' => $stores] , 200);
    }
    public function allStoresSimple(Request $request)
    {
        $type = $request->has('store_type') ? $request->store_type : 'product';
        $stores = Store::join('users', 'users.id', '=', 'store.user_id')
        ->where('status', 'approved')
        ->where('visible', 1)
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.slug', 'store.user_name', 'store.activity_type', 'store.created_at')
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store.id = store_rate.store_id
            ) as rate'))
            ->where('store_type' , $type)
            ->simplePaginate(20);
            foreach($stores as $store){
            $store->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $store->thumbnail_photo;
            }
        return response()->json(['status' => 200, 'stores' => $stores], 200);
    }
    public function getStoreMarketers($store_id){
        $store = Store::find($store_id);
        if(!$store || $store->store_type == "market"){
            return response()->json(['status' => 404 , 'errors' => ['فروشگاه مورد نظر یافت نشد']], 200);
        }
        $storeProducts = ProductSeller::join('store', 'product_seller.store_id', '=', 'store.id')
            ->where('store.id', $store->id)
            ->where('store.status', 'approved')
            ->select('product_seller.id')
            ->pluck('id');
        $markets = MarketProduct::with('market', 'market.user', 'product')->whereIn('product_id', $storeProducts)->simplePaginate(20)->where('market.status', 'approved');
        foreach ($markets as $market) {
            if($market->user)
            $market->user->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $market->user->thumbnail_photo;
            unset($market->id);
            unset($market->product_id);
            unset($market->market_id);
            unset($market->created_at);
            unset($market->updated_at);
        }
        return response()->json(['status' => 200 , 'markets' => $markets] , 200);
    }
}
