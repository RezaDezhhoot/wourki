<?php

namespace App\Http\Controllers;

use App\Address;
use App\AdsPosition;
use App\Category;
use App\Discount;
use App\Events\DiscountSaved;
use App\Guild;
use App\Libraries\Swal;
use App\Plan;
use App\ProductSeller;
use App\PurchaseProducts\Strategy\Shipping\Tehran;
use App\PurchaseProducts\Strategy\Shipping\Towns;
use App\Store;
use App\Upgrade;
use App\UpgradePosition;
use App\UsedDiscount;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Log;
use PayPal\Api\ShippingCost;
use Throwable;
use Validator;

class DiscountController extends Controller
{
    //admin controllers
    public function index(Request $request){
        $discounts = Discount::where('admin_made' , true)->orderByDesc('id')->paginate(20);
        return view('admin.discount.index' , compact('discounts'));
    }
    public function create(Request $request){
        $request->validate([
            'percentage' => 'required|integer|min:0',
            'code' => 'required|string|unique:discounts,code',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discountable_id' => 'integer',
            'discountable_type' => ['required' , Rule::in(Discount::types)],
            'type' => 'required|in:percentage,rial',
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
            'send_message' => 'string'
        ]);

        try{
        if($request->discountable_type == 'all' || $request->discountable_type == "all-product" || $request->discountable_type == "all-services" || $request->discountable_type == "all-ads" || $request->discountable_type == "all-plans" || $request->discountable_type == "all-upgrade" || $request->discountable_type == "all-sending"){
            $request->discountable_id = 0;
        }
        else{
            if(!$request->has('discountable_id')){
                    Swal::error('خطا', 'لطفا آیتم مورد تخفیف را به درستی وارد نمایید');
                    return redirect()->back();
            }
            else{
                    if (!Discount::chackForExistance($request->discountable_type, $request->discountable_id)) {
                        Swal::error('خطا', 'آیتم مورد تخفیف یافت نشد');
                        return redirect()->back();
                    }
            }
        }
        $discount = Discount::create($request->all());
        if($request->send_message === "on"){
            event(new DiscountSaved($discount));
        }
        Swal::success('موفق' , 'تخفیف با موفقیت ایجاد شد');
        return redirect()->back();
        }
        catch(Throwable $e){
            Swal::error('خطا', $e->getMessage());
            return redirect()->back();
        }
    }
    public function update(Request $request , $id){
        $request->validate([
            'percentage' => 'required|integer|min:0',
            'code' => 'required|string',
            'name' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|in:percentage,rial',
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0'
            // 'discountable_id' => 'required|integer',
            // 'discountable_type' => ['required', Rule::in(Discount::types)]
        ]);
        if(Discount::find($id)->code != $request->code){
            if(Discount::where('code' , $request->code)->first()->exists()){
                Swal::error('خطا', 'کد تخفیف از قبل استفاده شده است');
                return redirect()->back();
            }
        }
        // if (!Discount::chackForExistance($request->discountable_type, $request->discountable_id)) {
        //     Swal::error('خطا', 'آیتم مورد تخفیف یافت نشد');
        //     return redirect()->back();
        // }
        Discount::where('id' , $id)->first()->update($request->all());
        Swal::success('موفق', 'تخفیف با موفقیت ویرایش شد');
        return redirect()->back();
    }
    public function delete($id){
        Discount::where('id' , $id)->delete();
        Swal::success('موفق', 'تخفیف با موفقیت حذف شد');
        return redirect()->back();
    }
    public function getDiscountables(Request $request , $type){
        if ($type == "guild") {
            return response()->json(['data' => Guild::select('id' , 'name')->get()],200);
        }
        if ($type == "category") {
            return response()->json(['data' => Category::select('id' , 'name')->get()],200);
        }
        if ($type == "product" || $type == "service" ) {
            return response()->json(['data' => ProductSeller::join('store', 'product_seller.store_id', '=', 'store.id')
                ->where('store_type', $type)->select('product_seller.id' , 'product_seller.name')->get()],200);
        }
        if ($type == "product-sending") {
            return response()->json(['data' => ProductSeller::join('store', 'product_seller.store_id', '=', 'store.id')
            ->select('product_seller.id', 'product_seller.name')->get()], 200);
        }
        if ($type == "store" || $type == "store-sending") {
            return response()->json(['data' => Store::select('id' , 'name')->get()],200);
        }
        if ($type == "ad") {
            return response()->json(['data' => AdsPosition::select('id', 'name')->get()], 200);
        }
        if ($type == "plan") {
            return response()->json(['data' => Plan::where('status' , 'show')->select('id', 'plan_name as name')->get()], 200);
        }
        if($type == "upgrade") {
            return response()->json(['data' => UpgradePosition::select('id' , 'name')->get()] , 200);
        }
        return response()->json(['errors' => ['type']] , 200);
    }
    public function validateDiscount(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:product-service,ad,plan,upgrade',
            'code' => 'required',
            'id' => 'required|nullable',
            'price' => 'integer',
            'address' => 'nullable|exists:address,id'
        ]);
        if($request->id == "null"){
            $request->id = null;
        }
        if($validator->fails()){
            return response()->json(['errors' => [$validator->errors()->all()]] , 400);
        }
        $is_sending = false;
        $sumPrice = null;
        if($request->type == 'product-service'){
            $discount = Discount::getDiscountFor($request->code, 'product' , $request->id);

            if (is_null($discount)) {
                $discount = Discount::getDiscountFor($request->code, 'service' , $request->id);
                if (is_null($discount)) {
                    $discount = Discount::getDiscountFor($request->code , 'sending' , $request->id);
                    $is_sending = true;
                    if(is_null($discount))
                    return response()->json(['errors', ['تخفیف مورد نظر یافت نشد']], 404);
                }
            }
            $user = auth()->guard('api')->user();
            if(!$is_sending){
            $carts = $user->carts;
            $carts->each(function ($cart){
                $cart->attributesProduct = $cart->attributes;
                $cart->totalPrice = ($cart->product->price - (($cart->product->price * $cart->product->discount) / 100)) * $cart->quantity;
            });
            $attrPrice = 0;
            $sumPrice = 0;
            foreach ($carts as $index => $cart) {
                $attrPrice = 0;
                foreach ($carts[$index]->attributesProduct as $indexx => $attribute) {
                    $attrPrice += $attribute->attribute->extra_price * $cart->quantity;
                    $carts[$index]->attributesProduct[$indexx]->extra_price = $attribute->attribute->extra_price;
                }
                $carts[$index]->totalPrice += $attrPrice;
                $discounted = Discount::getDiscountFor($request->code , $carts[$index]->product->store->store_type , $carts[$index]->product->id);
                if(is_null($discounted))
                $sumPrice += $carts[$index]->totalPrice;
                else
                $sumPrice += $discounted->applyOn($carts[$index]->totalPrice);
            }
            }
            else{
                //means it is a sending discount
                if(!$request->address){
                    return response()->json(['is_sending' => true],200);
                }
                $is_sending = true;
                $address = Address::find($request->address);
                $sumPrice = $address->city_id == 118 ? (new Tehran($discount))->shippingCost($user->id) : (new Towns($discount))->shippingCost($user->id);
            }
        }
        else{
            $discount = Discount::getDiscountFor($request->code, $request->type, $request->id);
            if (is_null($discount)) {
                return response()->json(['errors', ['تخفیف مورد نظر یافت نشد']], 404);
            }
        }
        if(!is_null($sumPrice))
        $sumPrice = floor($sumPrice);
        if($request->price && ((!is_null($discount->min_price) && $discount->min_price >= $request->price) || (!is_null($discount->max_price) && $discount->max_price <= $request->price))){
            return response()->json(['errors', ['تخفیف مورد نظر یافت نشد']], 404);
        }
        return response()->json(['data' => $discount , 'sumPrice' => $sumPrice ,'is_sending' => $is_sending] , 200);
    }
    public function getDiscountsPage(Request $request){
        $discounts = Discount::where('start_date' , '<' , Carbon::now())->where('end_date' , '>' , Carbon::now())->where('admin_made' , true)->get();
        return view('frontend.my-account.discounts.index' , compact('discounts'));
    }
    public function createUserStoreDiscount(Request $request){
        $request->validate([
            'store_id' => 'required|integer',
            'name' => 'required|string',
            'code' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|in:percentage,rial',
            'discount_for' => 'required|in:sending,self',
            'min_price' => 'nullable|integer|min:0',
            'max_price' => 'nullable|integer|min:0',
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
        $user = auth()->guard('web')->user();
        //finding store
        $store = Store::find($request->store_id);
        if(!$store || $store->user_id != $user->id){
            Swal::error('خطا' , 'فروشگاه مورد نظر یافت نشد');
            return redirect()->back();
        }
        if($request->type == "percentage" && $request->amount > 100){
            Swal::error('خطا', 'مقدار تخفیف در صورت درصدی بودن باید عددی بین 1 تا 100 انتخاب شود');
            return redirect()->back();
        }
        if (Discount::where('code', $request->code)->exists()) {
            Swal::error('خطا', 'این کد تخفیف از قبل وجود دارد');
            return redirect()->back();
        }
        try{
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
        Swal::success('موفق' , 'تخفیف مورد نظر با موفقیت ایجاد شد');
        return redirect()->back();
        }
        catch(Throwable $e){
            Swal::error('خطا' , 'مشکلی در ثبت تخفیف شما به وجود آمده است');
            return redirect()->back();
        }
    }
    public function createUserProductDiscount(Request $request){
        $request->validate([
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
        $user = auth()->guard('web')->user();
        //finding store
        $product = ProductSeller::find($request->product_id);
        if (!$product || $product->store->user_id != $user->id) {
            Swal::error('خطا', 'محصول / خدمت مورد نظر یافت نشد');
            return redirect()->back();
        }
        if ($request->type == "percentage" && $request->amount > 100) {
            Swal::error('خطا', 'مقدار تخفیف در صورت درصدی بودن باید عددی بین 1 تا 100 انتخاب شود');
            return redirect()->back();
        }
        if(Discount::where('code' , $request->code)->exists()){
            Swal::error('خطا', 'این کد تخفیف از قبل وجود دارد');
            return redirect()->back();
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
            Swal::success('موفق', 'تخفیف مورد نظر با موفقیت ایجاد شد');
            return redirect()->back();
        } catch (Throwable $e) {
            Swal::error('خطا', 'مشکلی در ثبت تخفیف شما به وجود آمده است');
            return redirect()->back();
        }
    }
    public function getUsedPage(Request $request){
        $list = UsedDiscount::join('users' , 'used_discounts.user_id' , '=' , 'users.id')
        ->join('discounts' , 'discount_id' , '=' , 'discounts.id');
        if($request->has('user_id') && $request->user_id != 0){
            $list->where('user_id' , $request->user_id);
        }
        if($request->has('status') && $request->status != 'all'){
            $list->where('status' , $request->status);
        }
        if($request->has('discount_code') && $request->discount_code != ''){
            $list->where('discounts.code' , $request->discount_code);;
        }
        if($request->has('pay_type') && $request->pay_type != 'all'){
            $list->where('pay_type' , $request->pay_type);
        }
        $users = User::all();
        $list = $list->select('used_discounts.*' , 'users.first_name' , 'users.last_name' , 'discounts.code' , 'discounts.name')->paginate(20);
        return view('admin.discount.used-discounts' , compact('list' , 'users'));
    }
    
}
