<?php

namespace App\Http\Controllers\ApiV2;

use App\Ads;
use App\AdsPosition;
use App\AdsStairs;
use App\Discount;
use App\Events\AdCreated;
use App\Helpers\LaravelCafebazaar\LaravelCafebazaar;
use App\Helpers\Myket;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\Setting;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Store;
use App\UsedDiscount;
use App\User;
use Exception;
use Gateway;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AdsApi extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ads_position_id' => 'required|numeric|exists:ads_position,id',
            'link_type' => 'required|string|in:store,product',
            'store_type' => 'required_if:link_type,store|string|in:product,service,market',
            'product_id' => 'nullable|numeric|exists:product_seller,id',
            'description' => 'required|string',
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

        $user = auth()->guard('api')->user();
        // if ($request->link_type == 'store' && !($user->store)) {
        //     return response()->json([
        //         'status' => 400,
        //         'message' => 'user_doesnt_have_a_store',
        //         'entire' => []
        //     ]);
        // }

        $ads = new Ads();
        $ads->ads_position_id = $request->ads_position_id;
        // if ($request->hasFile('pic')) {
        //     $imgName = uniqid() . '.' . $request->pic->getClientOriginalExtension();
        //     $request->pic->move(public_path('image/ads'), $imgName);
        //     $ads->pic = $imgName;
        // }
        $ads->link_type = $request->link_type;
        if($request->link_to == 'product'){
            $ads->product_id = $request->product_id;
        }else{
            $ads->store_id = optional(Store::where('user_id' ,$user->id)->where('store_type' , $request->store_type)->first())->id;
        }
        $ads->description = $request->description;
        $ads->user_id = auth()->guard('api')->user()->id;
        $ads->status = 'pending';
        $ads->pay_status = 'unpaid';
        $ads->save();
        return response()->json([
            'status' => 200,
            'message' => 'ads_saved',
            'entire' => [
                'ads' => $ads,
                'payment_url' => route('ads.payment_gateway_init' , [$ads->id])
            ]
        ]);
    }
    public function PaymentGatewayInit(Request $request , $ad_id){
        $request->session()->put('ad_id' , $ad_id);
        $ads = Ads::find($ad_id);
        $position = AdsPosition::find($ads->ads_position_id);
        if(!$ads){
            return 'ad not found';
        }
        $gateway = Gateway::zarinpal();
        $gateway->setCallback(route('ads.payment_gateway_callback'));
        $gateway->price($position->price * 10)
            ->ready();

        return $gateway->redirect();
    }
    public function PaymentGatewayCallback(Request $request){
        try{
        $ads = Ads::find($request->session->get('ad_id'));
        $position = AdsPosition::find($ads->ads_position_id);
        \DB::beginTransaction();
        $payment = new AdsStairs();
        $payment->ads_id = $ads->id;
        $payment->pay_date = Carbon::now()->toDateString();
        $payment->initial_pay = 'initial';
        $payment->price = $position->price;
        $payment->payment_type = 'online';
        $payment->save();
        $ads->pay_status = 'paid';
        $ads->save();
        event(new AdCreated($ads , $payment->price));
        $gateway = Gateway::verify();
        $trackingCode = $gateway->trackingCode();
        $payment->tracking_code = $trackingCode;
        $request->session()->put('gateway_tracking_code', $trackingCode);
        $request->session()->put('cart_payment_date', \jdate()->format('%d %B %Y'));
        \DB::commit();
        return redirect()->route('ads.payment_gateway_finalize');
    }
    catch(Exception $e){
        return $e->getMessage();
    }
    }
    public function PaymentGatewayFinalize(Request $request){
        $ads = Ads::find($request->session()->get('ad_id'));
        return view('app.ads-payment-successful', compact('plan'));

    }
    public function userAds(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'store_type' => ['nullable' , 'in:product,service,market']
        ]);
        if($validator->fails()){
            return response()->json(['status' => 400 , 'errors' => $validator->errors()->all()] , 200);
        }
        $user = User::find(auth()->guard('api')->user()->id);
        if($request->has('store_type')){
            $ads = Ads::where('link_type' , 'store')->join('store' , 'ads.store_id' , '=' , 'store.id')
                ->where('store.user_id' , $user->id)
                ->where('store_type' , $request->store_type)
                ->where('ads.status', '!=', 'deleted')
                ->select('ads.*')
                ->paginate(15);
        }
        else{
            $ads = $user->ads()
            ->where('ads.status', '!=', 'deleted')
            ->paginate(15);
        }
        
        
        foreach ($ads as $index => $ad) {
            if ($ad->pic) {
                $ads[$index]->pic = url()->to('/image/ads') . '/' . $ad->pic;
            }
            if ($ad->final_pic) {
                $ads[$index]->final_pic = url()->to('/image/ads') . '/' . $ad->final_pic;
            }
            $ad->position;
            $ad->product;
            $ad->store;
            $ad->user;
            $ad->payments;
        }
        return response()->json([
            'status' => 200,
            'message' => 'ads_returned',
            'entire' => $ads
        ]);
    }

    public function destroy(Ads $ads)
    {
        $ads->status = 'deleted';
        $ads->save();

        return response()->json([
            'status' => 200,
            'message' => 'ads_deleted',
            'entire' => []
        ]);
    }

    public function getHomePageAds()
    {

        $list = AdsPosition::with(['ads' => function ($query) {

            $query->where('pay_status', 'paid')->where('status', 'approved')
                // ->whereRaw('(
                //     select count(*) 
                //     from ads_stairs
                //     where ads_stairs.ads_id = ads.id
                // ) > 0 ')
                ->select('ads.*')
                ->groupBy('ads.id' , 'ads.ads_position_id' , 'ads.pic', 'ads.final_pic' , 'ads.link_type' ,
                    'ads.product_id' , 'ads.store_id' , 'ads.description' , 'ads.pay_status' , 'ads.payment_type' , 'ads.status',
                    'ads.expire_date' , 'ads.user_id' , 'ads.created_at' , 'ads.updated_at')
                ->join('ads_stairs' , 'ads_stairs.ads_id' , '=' , 'ads.id');
                // ->where('ads.expire_date', '>=', Carbon::now()->toDateString());
        },
            'ads.product',
            'ads.product.photos',
            'ads.store',
            'ads.store.photo',
            'ads.store.address' => function ($query) {
                $query->select('address.*' , 'city.name as city_name' , 'province.name as province_name')
                    ->join('city' , 'city.id' , 'address.city_id')
                    ->join('province' , 'province.id' , '=' , 'city.province_id');
            },
        ])
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'ads_returned',
            'entire' => [
                'ads' => $list
            ]
        ]);
    }

    public function payWithWallet(Request $request , Ads $ads){
        $user = auth()->guard('api')->user();
        $walletStock = Wallet::where('user_id' , $user->id)
            ->sum('cost');
        $adsPrice = $ads->position->price;
        if($walletStock < $adsPrice){
            return response()->json([
                'status' => 400,
                'message' => 'wallet_stock_is_not_enough'
            ]);
        }
        $wallet = Wallet::create([
            'user_id' => $user->id,
            'cost' => -1 * $adsPrice,
            'wallet_type' => 'buy_ad',
        ]);

        //wallet reduce
        $walletHandler = new WalletHandler();
        if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
            $wallet->reducedItem()->attach($data);
        }

        $adsIntervalDays = Setting::first()->ads_expire_days;

        $ads->pay_status = 'paid';
        $ads->payment_type = 'wallet';
        $ads->expire_date = Carbon::now()->addDays($adsIntervalDays)->toDateString();
        $ads->save();

        $ads->payments()->create([
            'payment_type' => 'wallet',
            'pay_date' => Carbon::now()->toDateString(),
            'initial_pay' => 'initial',
            'price' => $adsPrice,
        ]);
        event(new AdCreated($ads , $adsPrice));
        return response()->json([
            'status' => 200,
            'message' => 'successfully_paid'
        ]);
    }

    public function makeStairs(Request $request , Ads $ads){
        $validator = Validator::make($request->all() , [
            'discount' => 'nullable|exists:discounts,id',
            'sku' => 'required|string',
            'purchase_token' => 'required|string',
            'market' => 'required|in:bazaar,myket'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 400 , 'errors' => $validator->errors()->all()] , 200);
        }
        $user = auth()->guard('api')->user();
        $position = AdsPosition::find($ads->ads_position_id);
        $payment = new AdsStairs();
        $payment->ads_id = $ads->id;
        $payment->pay_date = Carbon::now()->toDateString();
        $payment->initial_pay = 'stairs';
        $payment->price = $position->price;
        //check for discount
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount && $request->discount != null) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'ad', $position->id);
            if (!is_null($discount)) {
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $user->id;
                $usedDiscount->discount_id = $discount->id;
                $usedDiscount->price = $payment->price;
                $payment->price = $discount->applyOn($payment->price);
                $usedDiscount->price_with_discount = $payment->price;
            }
        }
        $payment->payment_type = 'other';
        if ($request->market == "myket") {
            $exists = AdsStairs::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'myket')->exists();
            if ($exists) {
                return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
            }
            $myket = new Myket();
            $result = $myket->verifyPurchase($request->purchase_token, $request->sku);
            if (!$result) {
                return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
            }
            $payment->purchase_token = $request->purchase_token;
            $payment->in_app_purchase_market_type = 'myket';
            $payment->save();
        } else {
            $exists = AdsStairs::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'bazaar')->exists();
            if ($exists) {
                return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
            }
            $cafebazaar = new LaravelCafebazaar();
            $purchase = $cafebazaar->verifyPurchase(env('CAFE_BAZZAR_PACKAGE_NAME'), $request->sku, $request->purchase_token);
            if (!$purchase->isValid()) {
                return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
            }
            $payment->purchase_token = $request->purchase_token;
            $payment->in_app_purchase_market_type = 'bazaar';
            $payment->save();
        }
        event(new AdCreated($ads, $payment->price));
        if ($usedDiscount) {
            $usedDiscount->pay_type = 'other';
            $usedDiscount->save();
        }
        return response()->json(["status" => 200] , 200);
    }
    
    public function saveAdForUser(Request $request){
        $validator = Validator::make($request->all(), [
            'ad_position_id' => 'required|exists:ads_position,id',
            'sku' => 'required|string',
            'purchase_token' => 'required|string',
            'market' => 'nullable|in:bazaar,myket'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }

        // chacking for valid purchase
        if($request->market == "myket"){
            $exists = Ads::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'myket')->exists();
            if ($exists) {
                return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
            }
            $myket = new Myket();
            $result = $myket->verifyPurchase($request->purchase_token , $request->sku);
            if(!$result){
                return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
            }
            $payload = $myket->getPayload();
            $user = auth()->guard('api')->user();
            $position = AdsPosition::find($request->ad_position_id);
            $ads = new Ads();
            $ads->user_id = $user->id;
            $ads->ads_position_id = $position->id;
            $ads->pay_status = 'paid';
            // $ads->pic
            try{
            $ads->description = $payload['description'];
            $ads->link_type = $payload['link_type'];
            $ads->store_type = $payload['store_type'];
            $ads->product_id = $payload['product_id'];
            $ads->store_id = $payload['store_id'];
            $ads->save();
            }
            catch(Throwable $e){
                return response()->json(['status' => '400' , 'errors' => ['اطلاعات تبلیغ خریداری شده یافت نشد']], 200);
            }
        }
        else{
            $exists = Ads::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'bazaar')->exists();
            if ($exists) {
                return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
            }
            $cafebazaar = new LaravelCafebazaar();
            $purchase = $cafebazaar->verifyPurchase(env('CAFE_BAZZAR_PACKAGE_NAME'), $request->sku, $request->purchase_token);
            if (!$purchase->isValid()) {
                return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
            }
            $payload = $purchase->getPayload();
            $user = auth()->guard('api')->user();
            $position = AdsPosition::find($request->ad_position_id);
            $ads = new Ads();
            $ads->user_id = $user->id;
            $ads->ads_position_id = $position->id;
            $ads->pay_status = 'paid';
            // $ads->pic
            $ads->description = $payload->description;
            $ads->link_type = $payload->link_type;
            $ads->store_type = $payload->store_type;
            $ads->product_id = $payload->product_id;
            $ads->store_id = $payload->store_id;
            $ads->save();
        }
        event(new AdCreated($ads , $position->price));
        return response()->json([
            'status' => 200,
            'message' => 'ads_has_been_saved',
            'entire' => []
        ]);
    }
}
