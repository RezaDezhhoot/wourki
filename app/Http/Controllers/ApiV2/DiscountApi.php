<?php

namespace App\Http\Controllers\ApiV2;

use App\Discount;
use App\Helpers\DiscountQueryHelper;
use App\Http\Controllers\Controller;
use App\ProductSeller;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Log;
use Throwable;
use Validator;

class DiscountApi extends Controller
{
    public function getHomepageDiscounts(){
        $discountsQueryHelper = new DiscountQueryHelper();
        $discounts = $discountsQueryHelper->getAllDiscountsMadeByUsers();
        return response()->json(['status' => 200 , 'discounts' => $discounts] , 200);
    }
    public function getAvailableDiscounts()
    {
        $discounts = Discount::where('start_date', '<', Carbon::now())->where('end_date', '>', Carbon::now())->where('admin_made' , true)->get();
        return response()->json(['status' => 200,'discounts' => $discounts] , 200);
    }
    public function deleteDiscount( $discount_id){
        $user = auth()->guard('api')->user();
        Log::info('delete called for' . strval($discount_id));
        $discount = Discount::where('admin_made' , false)->where('discounts.id' , $discount_id)->whereIn('discountable_type' , ['store' , 'store-sending'])
        ->join('store' , 'discountable_id' , '=' , 'store.id')->where('user_id' , $user->id)->exists();
        if($discount){
            Log::info('store discount found');
            Discount::where('id' , $discount_id)->delete();
            return response()->json([ "status" => 200] , 200);
        }
        $discount = Discount::where('admin_made', false)->where('discounts.id', $discount_id)->whereIn('discountable_type', ['service' , 'product' , 'product-sending'])
        ->join('product_seller', 'discountable_id', '=', 'product_seller.id')
        ->join('store' , 'product_seller.store_id' ,'=' , 'store.id')
        ->where('user_id', $user->id)
        ->exists();
        if($discount){
            Log::info('product discount found');
            Discount::where('id', $discount_id)->delete();
            return response()->json([ "status" => 200] , 200);
        }
        return response()->json(["status" => 404],200);
    }
    public function createStoreDiscount(Request $request){
        $validator = Validator::make($request->all() , [
            'store_id' => 'required|integer',
            'name' => 'required|string',
            'code' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|in:percentage,rial',
            'discount_for' => 'required|in:sending,self',
            'min_price' => 'required|integer|min:0',
            'max_price' => 'required|integer|min:0',
            'amount' => 'required|integer|min:0',
        ], [
            'name.required' => 'لطفا موضوع تخفیف را وارد نمایید',
            'code.required' => 'لطفا کد تخفیف را وارد نمایید',
            'start_date.required' => 'لطفا تاریخ شروع تخفیف را وارد نمایید',
            'end_date.required' => 'لطفا تاریخ پایان تخفیف را وارد نمایید',
            'min_price.required' => 'لطفا حداقل قیمت اعمال تخفیف را وارد نمایید',
            'max_price.required' => 'لطفا حداکثر قیمت تخفیف را وارد نمایید',
            'amount.required' => 'لطفا میزان تخفیف را وارد نمایید'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $user = auth()->guard('api')->user();
        //finding store
        $store = Store::find($request->store_id);
        if (!$store || $store->user_id != $user->id) {
            return response()->json(['status' => 404, 'errors' => ['فروشگاه مورد نظر یافت نشد']]);
        }
        if($store->store_type == 'market'){
            return response()->json(['status' => 400, 'errors' => ['بر روی فروشگاه بازاریابی نمیتوان تخفیف تعریف کرد']]);
        }
        if ($request->type == "percentage" && $request->amount > 100) {
            return response()->json(['status' => 400, 'errors' => ['مقدار تخفیف در صورت درصدی بودن باید عددی بین 1 تا 100 انتخاب شود']] , 200);

        }
        if (Discount::where('code', $request->code)->exists()) {
            return response()->json(['status' => 400 , 'errors' => ['این کد تخفیف از قبل وجود دارد']] , 200);
        }
        try {
            $discount = new Discount();
            $discount->name = $request->name;
            $discount->code = $request->code;
            $discount->type = $request->type;
            $discount->percentage = $request->amount;
            $discount->start_date = $request->start_date;
            $discount->end_date = $request->end_date;
            $discount->min_price = $request->min_price;
            $discount->max_price = $request->max_price;
            $discount->discountable_type =  $request->discount_for == 'self' ? 'store' : 'store-sending';
            $discount->discountable_id = $store->id;
            $discount->admin_made = false;
            $discount->description = $request->description;
            $discount->save();
            return response()->json(['status' => 200 , 'discount' => $discount] , 201);
        } catch (Throwable $e) {
            Log::info('error happened in store discount maker : ' . $e->getMessage());
            return response()->json(['status' => 404, 'errors' => ['مشکلی به وجود آمده است']]);
        }
    }
    public function createProductDiscount(Request $request){
        $validator = Validator::make($request->all() ,[
            'product_id' => 'required|integer',
            'name' => 'required|string',
            'code' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|in:percentage,rial',
            'discount_for' => 'required|in:sending,self',
            'amount' => 'required|integer|min:0',
        ], [
            'name.required' => 'لطفا موضوع تخفیف را وارد نمایید',
            'code.required' => 'لطفا کد تخفیف را وارد نمایید',
            'start_date.required' => 'لطفا تاریخ شروع تخفیف را وارد نمایید',
            'end_date.required' => 'لطفا تاریخ پایان تخفیف را وارد نمایید',
            'amount.required' => 'لطفا میزان تخفیف را وارد نمایید'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $user = auth()->guard('api')->user();
        //finding store
        $product = ProductSeller::find($request->product_id);
        if (!$product || $product->store->user_id != $user->id) {
            return response()->json(['status' => 404, 'errors' => ['محصول / خدمت مورد نظر یافت نشد']]);
        }
        if ($request->type == "percentage" && $request->amount > 100) {
            return response()->json(['status' => 400, 'errors' => ['مقدار تخفیف در صورت درصدی بودن باید عددی بین 1 تا 100 انتخاب شود']], 200);
        }
        if (Discount::where('code', $request->code)->exists()) {
            return response()->json(['status' => 400, 'errors' => ['این کد تخفیف از قبل وجود دارد']], 200);

        }
        try {
            $discount = new Discount();
            $discount->name = $request->name;
            $discount->code = $request->code;
            $discount->type = $request->type;
            $discount->percentage = $request->amount;
            $discount->start_date = $request->start_date;
            $discount->end_date = $request->end_date;
            $discount->min_price = $product->price;
            $discount->max_price = $product->price;
            $discount->discountable_type = $request->discount_for == 'self' ? $product->store->store_type : 'product-sending';
            $discount->discountable_id = $product->id;
            $discount->admin_made = false;
            $discount->description = $request->description;
            $discount->save();
            return response()->json(['status' => 200, 'discount' => $discount], 201);
        } catch (Throwable $e) {
            Log::info('error happened in product discount maker : ' . $e->getMessage());
            return response()->json(['status' => 404, 'errors' => ['مشکلی به وجود آمده است']]);
        }
    }
    public function getStoreDiscounts($store_id){
        $store = Store::find($store_id);
        if(!$store || $store->user_id != auth()->guard('api')->user()->id){
            return response()->json(['status' => 404] , 200);
        }
        $discounts = Discount::where('admin_made', false)->whereIn('discountable_type', ['store', 'store-sending'])->where('discountable_id', $store->id)->paginate(20);
        foreach($discounts as $discount){
            unset($discount->admin_made);
        }
        return response()->json(['status' => 200 , 'discounts' => $discounts] , 200);
    }
    public function getProductDiscounts($product_id){
        $product = ProductSeller::find($product_id);
        if (!$product|| $product->store->user_id != auth()->guard('api')->user()->id) {
            return response()->json(['status' => 404], 200);
        }
        $discounts = Discount::where('admin_made', false)->whereIn('discountable_type', ['product', 'product-sending'])->where('discountable_id', $product->id)->paginate(20);
        foreach ($discounts as $discount) {
            unset($discount->admin_made);
        }
        return response()->json(['status' => 200, 'discounts' => $discounts], 200);

    }
}
