<?php

namespace App\Http\Controllers\ApiV2;

use App\Discount;
use App\Events\UpgradeCreated;
use App\Helpers\LaravelCafebazaar\LaravelCafebazaar;
use App\Helpers\Myket;
use App\Http\Controllers\Controller;
use App\ProductSeller;
use App\Store;
use App\Upgrade;
use App\UpgradePosition;
use App\UsedDiscount;
use App\User;
use DB;
use Gateway;
use Illuminate\Http\Request;
use Throwable;
use Validator;

class UpgradeApi extends Controller
{
    public function positions(Request $request){
        if($request->has('type')){
            $positions = UpgradePosition::whereRaw("position LIKE '%".$request->type."%'")->get();
        }
        else{
            $positions = UpgradePosition::all();
        }
        return response()->json(['status' => 200 ,'positions' => $positions] , 200);
    }
    public function upgradeProduct(Request $request){
        $validator = Validator::make($request->all() , [
            'pay_type' => 'required|in:online,in_app',
            'market' => 'nullable|in:bazaar,myket',
            'product_id' => 'required|exists:product_seller,id',
            'position_id' => 'required|exists:upgrade_positions,id',
            'sku' => 'string',
            'purchase_token' => 'string',
            'discount' => 'nullable|exists:discounts,id'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 400 , 'errors' => $validator->errors()->all()] , 200);
        }
        $user = User::find(auth()->guard('api')->user()->id);
        $product = ProductSeller::find($request->product_id);
        $storeType = $product->store->store_type;
        // if($product->store->user_id != $user->id && !$request->from_marketer){
        //     return response()->json(['status' => 400 , 'errors' => ['شما مالک محصول نیستید']] , 200);
        // }
        $position = UpgradePosition::find($request->position_id);
        $price = $position->price;
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'upgrade', $position->id);
            if (!is_null($discount)) {
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $user->id;
                $usedDiscount->discount_id = $discount->id;
                $usedDiscount->price = $price;
                $price = $discount->applyOn($price);
                $usedDiscount->price_with_discount = $price;
            }
        }
        if (!str_contains($position->position, $storeType)) {
            return response()->json(['status' => 400, 'errors' => ['جایگاه انتخابی با آیتم انتخاب شده مطابقت ندارد']] , 200);

        }
        DB::beginTransaction();
        try {
            if($request->pay_type == "online"){
                $upgrade = new Upgrade();
                $upgrade->upgrade_position_id = $request->position_id;
                $upgrade->upgradable_type = ProductSeller::class;
                $upgrade->upgradable_id = $product->id;
                $upgrade->status = 'pending';
                $upgrade->pay_type = 'online';
                $upgrade->price = $price;
                $upgrade->save();
                if ($usedDiscount) {
                    $usedDiscount->pay_type = 'online';
                    $usedDiscount->status = 'pending';
                    $usedDiscount->save();
                    $request->session()->put('used_discount_id', $usedDiscount->id);
                }
                DB::commit();
                return response()->json(['status' => 200 , 'payment_url' => route('buy_upgrade.payment_gateway_init') . '?upgrade_id=' . strval($upgrade->id) . '&payer=' . strval($user->id)] , 200);
            }
            else{
                if($request->market == 'myket'){
                    // doing in_app purchase
                    if (!$request->sku || !$request->purchase_token) {
                        return response()->json(['status' => 400, 'errors' => ['sku & purchase_token are required']], 200);
                    }
                    $exists = Upgrade::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type' , 'myket')->where('status', 'approved')->exists();
                    if ($exists) {
                        return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
                    }
                    $myket = new Myket();
                    $result = $myket->verifyPurchase($request->purchase_token , $request->sku);
                    if(!$result){
                        return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
                    }
                }
                else{
                    // doing in_app purchase
                    if (!$request->sku || !$request->purchase_token) {
                        return response()->json(['status' => 400, 'errors' => ['sku & purchase_token are required']], 200);
                    }
                    $exists = Upgrade::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'bazaar')->where('status', 'approved')->exists();
                    if ($exists) {
                        return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
                    }
                    $cafebazaar = new LaravelCafebazaar();
                    $purchase = $cafebazaar->verifyPurchase(env('CAFE_BAZZAR_PACKAGE_NAME'), $request->sku, $request->purchase_token);
                    if (!$purchase->isValid()) {
                        return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
                    }
                }
                $upgrade = new Upgrade();
                $upgrade->upgrade_position_id = $request->position_id;
                $upgrade->upgradable_type = ProductSeller::class;
                $upgrade->upgradable_id = $product->id;
                $upgrade->pay_type = 'other';
                $upgrade->status = 'approved';
                $upgrade->price = $price;
                if($request->market == "bazaar")
                $upgrade->in_app_purchase_market_type = 'bazaar';
                else
                $upgrade->in_app_purchase_market_type = 'myket';
                $upgrade->purchase_token = $request->purchase_token;
                $upgrade->save();
                if ($usedDiscount) {
                    $usedDiscount->pay_type = 'other';
                    $usedDiscount->save();
                }
                event(new UpgradeCreated($upgrade));
                DB::commit();
                return response()->json([ "status" => 200], 200);
            }

        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 400, 'errors' => [$e->getMessage()]]);
        }
    }
    public function upgradeStore(Request $request){
        $validator = Validator::make($request->all(), [
            'pay_type' => 'required|in:online,in_app',
            'market' => 'nullable|in:bazaar,myket',
            'store_id' => 'required|exists:store,id',
            'position_id' => 'required|exists:upgrade_positions,id',
            'sku' => 'string',
            'purchase_token' => 'string',
            'discount' => 'nullable|exists:discounts,id',
            'from_marketer' => 'nullable|boolean'

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $user = User::find(auth()->guard('api')->user()->id);
        $store = Store::find($request->store_id);
        // if ($store->user_id != $user->id && !$request->from_marketer) {
        //     return response()->json(['status' => 400, 'errors' => ['شما مالک فروشگاه نیستید']] , 200);
        // }
        $position = UpgradePosition::find($request->position_id);
        $price = $position->price;
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'upgrade', $position->id);
            if (!is_null($discount)) {
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $user->id;
                $usedDiscount->discount_id = $discount->id;
                $usedDiscount->price = $price;
                $price = $discount->applyOn($price);
                $usedDiscount->price_with_discount = $price;
            }
        }
        if (!str_contains($position->position, "store")) {
            return response()->json(['status' => 400, 'errors' => ['جایگاه انتخابی با آیتم انتخاب شده مطابقت ندارد']] , 200);
        }
        DB::beginTransaction();
        try {
            if ($request->pay_type == "online") {
                $upgrade = new Upgrade();
                $upgrade->upgrade_position_id = $request->position_id;
                $upgrade->upgradable_type = Store::class;
                $upgrade->upgradable_id = $store->id;
                $upgrade->status = 'pending';
                $upgrade->pay_type = 'online';
                $upgrade->price = $price;
                if ($request->from_marketer) {
                    $upgrade->from_marketer = optional(Store::where('store_type', 'market')->where('user_id', $user->id)->first())->id;
                }
                $upgrade->save();
                if ($usedDiscount) {
                    $usedDiscount->pay_type = 'online';
                    $usedDiscount->status = 'pending';
                    $usedDiscount->save();
                    $request->session()->put('used_discount_id', $usedDiscount->id);
                }
                DB::commit();
                return response()->json(['status' => 200, 'payment_url' => route('buy_upgrade.payment_gateway_init') . '?upgrade_id=' . strval($upgrade->id) . '&payer=' . strval($user->id)], 200);
            } else {
                if ($request->market == 'myket') {
                    // doing in_app purchase
                    if (!$request->sku || !$request->purchase_token) {
                        return response()->json(['status' => 400, 'errors' => ['sku & purchase_token are required']], 200);
                    }
                    $exists = Upgrade::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'myket')->where('status', 'approved')->exists();
                    if ($exists) {
                        return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
                    }
                    $myket = new Myket();
                    $result = $myket->verifyPurchase($request->purchase_token, $request->sku);
                    if (!$result) {
                        return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
                    }
                    $upgrade = new Upgrade();
                    $upgrade->upgrade_position_id = $request->position_id;
                    $upgrade->upgradable_type = Store::class;
                    $upgrade->upgradable_id = $store->id;
                    $upgrade->status = 'approved';
                    $upgrade->pay_type = 'other';
                    $upgrade->in_app_purchase_market_type = 'myket';
                    $upgrade->price = $price;
                    $upgrade->purchase_token = $request->purchase_token;
                    if ($request->from_marketer) {
                        $upgrade->from_marketer = optional(Store::where('store_type', 'market')->where('user_id', $user->id)->first())->id;
                    }
                    $upgrade->save();
                    if ($usedDiscount) {
                        $usedDiscount->pay_type = 'other';
                        $usedDiscount->save();
                    }
                    event(new UpgradeCreated($upgrade));
                    DB::commit();
                    return response()->json([ "status" => 200], 200);
                } else {
                    // doing in_app purchase
                    if (!$request->sku || !$request->purchase_token) {
                        return response()->json(['status' => 400, 'errors' => ['sku & purchase_token are required']], 200);
                    }
                    $exists = Upgrade::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'bazaar')->where('status', 'approved')->exists();
                    if ($exists) {
                        return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
                    }
                    $cafebazaar = new LaravelCafebazaar();
                    $purchase = $cafebazaar->verifyPurchase(env('CAFE_BAZZAR_PACKAGE_NAME'), $request->sku, $request->purchase_token);
                    if (!$purchase->isValid()) {
                        return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
                    }
                    $upgrade = new Upgrade();
                    $upgrade->upgrade_position_id = $request->position_id;
                    $upgrade->upgradable_type = Store::class;
                    $upgrade->upgradable_id = $store->id;
                    $upgrade->status = 'approved';
                    $upgrade->pay_type = 'other';
                    $upgrade->price = $price;
                    $upgrade->in_app_purchase_market_type = 'bazaar';
                    $upgrade->purchase_token = $request->purchase_token;
                    if ($request->from_marketer) {
                        $upgrade->from_marketer = optional(Store::where('store_type', 'market')->where('user_id', $user->id)->first())->id;
                    }
                    $upgrade->save();
                    if ($usedDiscount) {
                        $usedDiscount->pay_type = 'other';
                        $usedDiscount->save();
                    }
                    event(new UpgradeCreated($upgrade));
                    DB::commit();
                    return response()->json([ "status" => 200] , 200);
                }
            }
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json(['status' => 400, 'errors' => [$e->getMessage()]]);
        }
    }

    // payments controllers below
    public function buyUpgradePaymentGatewayInit(Request $request){
        $request->validate([
            'upgrade_id'=>'required|exists:upgrades,id',
            'payer'=>'required|exists:users,id'
        ]);
        $upgrade = Upgrade::find($request->upgrade_id);
        if($upgrade->upgradable_type == Store::class){
            if($upgrade->upgradable->user_id != $request->payer){
                return response()->json(['status' => 401 , 'errors' => ['شما مجاز به پرداخت این ارتقا نیستید']] , 200);
            }
        }
        else{
            if($upgrade->upgradable->store->user_id != $request->payer){
                return response()->json(['status' => 401 , 'errors' => ['شما مجاز به پرداخت این ارتقا نیستید']] , 200);
            }
        }
        session()->put('buying_upgrade_id' , $upgrade->id);
        $gateway = Gateway::zarinpal();
        $gateway->setCallback(route('buy_upgrade.payment_gateway_callback'));
        $gateway->price($upgrade->position->price * 10)
            ->ready();
        return $gateway->redirect();
    }
    public function buyUpgradePaymentGatewayCallback(Request $request){
        $upgrade = Upgrade::find(session()->get('buying_upgrade_id'));
        if(!$upgrade){
            return response()->json(['status' => '404' , 'errors' => ['ارتقا یافت نشد']], 404);
        }
        $gateway = \Gateway::verify();
        $trackingCode = $gateway->trackingCode();
        $upgrade->status = 'approved';
        $upgrade->tracking_code = $trackingCode;
        $upgrade->save();
        event(new UpgradeCreated($upgrade));
        $request->session()->put('gateway_tracking_code', $trackingCode);
        $request->session()->put('cart_payment_date', \jdate()->format('%d %B %Y'));
        return redirect()->route('buy_upgrade.payment_gateway_finalize');

    }
    public function buyUpgradePaymentGatewayFinalize(Request $request){
        $upgrade = Upgrade::find(session()->get('buying_upgrade_id'));
        if (!$upgrade) {
            return response()->json(['status' => '404', 'errors' => ['ارتقا یافت نشد']], 404);
        }
        return view('app.upgrade-payment-successful', compact('upgrade'));
    }
    public function getUpgradeHistory(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:store,product,service',
            'id' => 'required|integer'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        if($request->type == 'product' || $request->type == 'service'){
            $product = ProductSeller::find($request->id);
            if($product->store->store_type != $request->type){
                return response()->json(['status' => 404 , 'history' => []] , 200);
            }
            return response()->json(['status' => 200 , 'history' => $product->upgrades()->with('position')->get()] , 200);
        }
        else{
            $store = Store::find($request->id);
            return response()->json(['status' => 200 , 'history' => $store->upgrades()->with('position')->get()] , 200);
        }
    }
}
