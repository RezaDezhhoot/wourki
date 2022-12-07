<?php

namespace App\Http\Controllers\API;

use App\Address;
use App\Discount;
use App\Events\SubscriptionCreated;
use App\Plan;
use App\PlanSubscription;
use App\Process\PlanSubscriptions;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\Store;
use App\Store_photo;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Exception;
use App\Helpers\LaravelCafebazaar\LaravelCafebazaar;
use App\Helpers\Myket;
use App\UsedDiscount;
use Illuminate\Http\Request;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;
use Log;
use Validator;

class PlanSubApi extends Controller
{
    public function dayInterval(Request $request)
    {
        $user = auth()->guard('api')->user();
        // return response()->json(['user' => $user]);
        $store = Store::where('store_type' , $request->query('store_type') ? $request->query('store_type') : 'product')->where('user_id' , $user->id)->first();
        if ($store) {
            $planSubs = PlanSubscription::where('user_id', $user->id)->where('plan_type' , $request->store_type == 'market' ? 'market' : 'store')->latest()->first();
            if ($planSubs) {
                $planSubsFromDate = $planSubs->from_date;
                $planSubsToDate = $planSubs->to_date;
                $now = Carbon::now()->toDateString();
                $minDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsFromDate);
                $maxDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsToDate);
                if ($maxDateCarbon->toDateString() >= $now) {
                    $intervalDays = $maxDateCarbon->diffInDays(Carbon::now());
                } else {
                    $intervalDays = 0;
                }
            } else
                $intervalDays = 0;
        } else
            $intervalDays = 0;

        if ($store) {
            $address = Address::join('store', 'store.address_id', '=', 'address.id')
                ->join('city', 'city.id', 'address.city_id')
                ->join('province', 'province.id', 'city.province_id')
                ->where('store.id', $store->id)
                ->select('address.*', 'city.name as city_name', 'city.id as city_id', 'province.name as province_name', 'province.id as province_id')
                ->first();

            //no photos for store anymore
            // $photos = Store_photo::where('store_id', $stores->id)->get();
            // if ($photos) {
            //     foreach ($photos as $index => $photo) {
            //         $photos[$index]->photo_name = url()->to('/image/store_photos/') . '/' . $photo->photo_name;
            //     }
            // }
            $store->photo_url = url()->to('/image/store_photos/') . '/' . optional($store->photo)->photo_name;
            if ($user->thumbnail_photo) {
                $store->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $user->thumbnail_photo;
            }
            unset($store->photo);
            return response()->json(['day' => $intervalDays, 'store' => $store, 'address' => $address], 200);
        } else
            return response()->json(['day' => $intervalDays , 'store' => null , 'address' => null], 200);

    }

    public function buyNewPlan(Request $request)
    {
        $user = auth()->guard('api')->user();
        $plan = Plan::find($request->plan);
        if (!$plan) {
            return response()->json([
                'status' => 400,
                'message' => 'plan_is_invalid',
                'entire' => []
            ]);
        }
        $plan_type = $plan->type;
        $price = $plan->price;
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount) {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'plan', $plan->id);
            if (!is_null($discount)) {
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $user->id;
                $usedDiscount->discount_id = $discount->id;
                $usedDiscount->price = $price;
                $price = $discount->applyOn($price);
                $usedDiscount->price_with_discount = $price;
            }
        }
        if ($request->has('pay_type') && $request->pay_type == 'wallet' && $price != 0) {
            $userWallet = Wallet::where('user_id', $user->id)
                ->sum('cost');
            if ($userWallet < $price) {
                return response()->json(['status' => 401], 401);
            }
        }
        if($request->pay_type == 'online' && $price > 0){
            return response()->json(['status' => 200 ,'link' => route('buy_plan.payment_gateway_init').'?plan='.$plan->id.'&payer='. $user->id . (($request->has('discount') && $request->discount) ? '&discount='.$request->discount : '')]);
        }
        if ($price == 0 || ($request->has('pay_type') && $request->pay_type == 'wallet')) {
            $bought = PlanSubscription::join('seller_plans', 'seller_plans.id', '=', 'seller_plan_subscription_details.plan_id')
                ->join('users', 'users.id', '=', 'seller_plan_subscription_details.user_id')
                ->join('store', 'store.user_id', '=', 'users.id')
                ->where('seller_plans.id', '=', $plan->id)
                ->where('users.id', '=', $user->id)
                ->where('seller_plans.type' , $plan_type)
                ->first();
            if ($price == 0) {
                if (!$bought) {
                    if (PlanSubscriptions::storeHasPremiumSubscription($user , $plan_type)) {
                        $fromDate = PlanSubscriptions::getLastPlanSubscriptionExpirationDatePremium($user , $plan_type);
                        $fromDate = Carbon::createFromFormat('Y-m-d', $fromDate)->addDays(1)->toDateString();
                        $toDate = Carbon::createFromFormat('Y-m-d', $fromDate)->addMonths($plan->month_inrterval)->toDateString();

                        $subscription = new PlanSubscription();
                        $subscription->plan_id = $plan->id;
                        $subscription->user_id = $user->id;
                        $subscription->price = 0;
                        $subscription->plan_type = $plan->type;
                        $subscription->from_date = $fromDate;
                        $subscription->to_date = $toDate;
                        $subscription->pay_id = null;
                        $subscription->save();
                        event(new SubscriptionCreated($subscription));
                    } else {
                        $subscription = new PlanSubscription();
                        $subscription->plan_id = $plan->id;
                        $subscription->user_id = $user->id;
                        $subscription->price = 0;
                        $subscription->plan_type = $plan->type;
                        $subscription->from_date = Carbon::now()->toDateString();
                        $subscription->to_date = Carbon::now()->addMonths($plan->month_inrterval)->toDateString();
                        $subscription->pay_id = null;
                        $subscription->save();
                        event(new SubscriptionCreated($subscription));
                    }
                    return response()->json([
                        'status' => 201,
                        'message' => 'user_does_not_bought_the_plan',
                        'entire' => [

                        ]
                    ], 201);
                } else {
                    return response()->json([
                        'status' => 402,
                        'message' => 'free_plan_already_bought',
                        'entire' => [

                        ]
                    ]);
                }
            } else {
                if (PlanSubscriptions::storeHasPremiumSubscription($user , $plan_type)) {
                    $fromDate = PlanSubscriptions::getLastPlanSubscriptionExpirationDatePremium($user , $plan_type);
                    $fromDate = Carbon::createFromFormat('Y-m-d', $fromDate)->addDays(1)->toDateString();
                    $toDate = Carbon::createFromFormat('Y-m-d', $fromDate)->addMonths($plan->month_inrterval)->toDateString();

                    $subscription = new PlanSubscription();
                    $subscription->plan_id = $plan->id;
                    $subscription->user_id = $user->id;
                    $subscription->price = $price;
                    $subscription->from_date = $fromDate;
                    $subscription->plan_type = $plan->type;
                    $subscription->to_date = $toDate;
                    $subscription->pay_id = null;
                    $subscription->save();
                    event(new SubscriptionCreated($subscription));
                } else {
                    $subscription = new PlanSubscription();
                    $subscription->plan_id = $plan->id;
                    $subscription->user_id = $user->id;
                    $subscription->price = $price;
                    $subscription->plan_type = $plan->type;
                    $subscription->from_date = Carbon::now()->toDateString();
                    $subscription->to_date = Carbon::now()->addMonths($plan->month_inrterval)->toDateString();
                    $subscription->pay_id = null;
                    $subscription->save();
                    event(new SubscriptionCreated($subscription));
                }
                $wallet = Wallet::create([
                    'user_id' => $user->id,
                    'cost' => -1 * $price,
                    'wallet_type' => 'output',
                ]);

                //wallet reduce
                $walletHandler = new WalletHandler();
                if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
                    $wallet->reducedItem()->attach($data);
                }
                if ($usedDiscount) {
                    $usedDiscount->pay_type = 'wallet';
                    $usedDiscount->save();
                }
                return response()->json([
                    'status' => 202,
                    'message' => 'user_does_not_bought_the_plan',
                    'entire' => [

                    ]
                ], 201);
            }

        } else {
            return response()->json([
                'status' => 200,
                'message' => 'plan_is_not_free',
                'entire' => [

                ]
            ]);
        }

    }

    public function buyPlanPaymentGatewayInit(Request $request)
    {
        $plan = $request->plan;
        $user = User::find($request->payer);
        if(!$user){
            return response()->json([
                'status' => 400,
                'message' => 'user_is_invalid',
                'entire' => []
            ]);
        }
        $request->session()->put('payer', $user);
        $plan = Plan::find($plan);
        $request->session()->put('bought_plan_id', $plan->id);
        
        if($plan->price == 0){
            return response()->json([
                'status' => 400,
                'message' => 'plan_is_free',
                'entire' => []
            ]);
        }
        try {
            $price = $plan->price;
            $usedDiscount = null;
            if ($request->has('discount') && $request->discount) {
                $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'plan', $plan->id);
                if (!is_null($discount)) {
                    $usedDiscount = new UsedDiscount();
                    $usedDiscount->user_id = $user->id;
                    $usedDiscount->discount_id = $discount->id;
                    $usedDiscount->price = $price;
                    $price = $discount->applyOn($price);
                    $usedDiscount->price_with_discount = $price;
                }
            }
            $gateway = Gateway::zarinpal();
            $gateway->setCallback(route('buy_plan.payment_gateway_callback'));
            $gateway->price($price * 10)
                ->ready();
            if ($usedDiscount) {
                $usedDiscount->pay_type = 'online';
                $usedDiscount->status = 'pending';
                $usedDiscount->save();
                $request->session()->put('used_discount_id', $usedDiscount->id);
            }
            return $gateway->redirect();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }
    public function InAppPurchaseViaWallet(Plan $plan){
        $user = auth()->guard('api')->user();
        $walletStock = Wallet::where('user_id' , $user->id)->sum('cost');
        if($walletStock < $plan->price){
            return response()->json([
                'status' => 400,
                'message' => 'wallet_stock_is_not_enough',
                'entire' => []
            ]);
        }


        $subscription = new PlanSubscription();
        $subscription->plan_id = $plan->id;
        $subscription->user_id = $user->id;
        $subscription->plan_type = $plan->type;
        $subscription->price = $plan->price;
        $startDate = PlanSubscriptions::getLastPlanSubscriptionExpirationDate($user , $plan->type);
        if ($startDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', PlanSubscriptions::getLastPlanSubscriptionExpirationDate($user))->addDays(1);
        } else {
            $startDate = Carbon::now();
        }
        $subscription->from_date = $startDate->toDateString();
        $subscription->to_date = $startDate->addMonths($plan->month_inrterval)->toDateString();
        $subscription->pay_id = null;
        $subscription->tracking_code = null;
        $subscription->bazar_in_app_purchase = 1;
        $subscription->save();

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'cost' => -1 * $plan->price,
            'wallet_type' => 'buy_plan',
        ]);

        //wallet reduce
        $walletHandler = new WalletHandler();
        if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
            $wallet->reducedItem()->attach($data);
        }

        return response()->json([
            'status' => 200,
            'message' => 'plan_saved',
            'entire' => []
        ]);
    }
    public function savePlanForUser(Request $request){
        $validator = Validator::make($request->all() , [
            'plan_id' => 'required|exists:seller_plans,id',
            'sku' => 'required|string',
            'purchase_token' => 'required|string',
            'market' => 'nullable|in:bazaar,myket'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 400 , 'errors' => $validator->errors()->all()],200);
        }

        $user = auth()->guard('api')->user();
        $plan = Plan::find($request->plan_id);
        if($request->market == 'myket'){
            // chacking for valid myket purchase
            $exists = PlanSubscription::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'myket')->exists();
            if ($exists) {
                return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
            }
            $myket = new Myket();
            $result = $myket->verifyPurchase($request->purchase_token , $request->sku);
            if (!$result) {
                return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
            }
        }
        else{
            // chacking for valid bazaar purchase
            $exists = PlanSubscription::where('purchase_token', $request->purchase_token)->where('in_app_purchase_market_type', 'bazaar')->exists();
            if ($exists) {
                return response()->json(['status' => 400, 'errors' => 'از این پرداخت قبلا استفاده شده'], 200);
            }
            $cafebazaar = new LaravelCafebazaar();
            $purchase = $cafebazaar->verifyPurchase(env('CAFE_BAZZAR_PACKAGE_NAME'), $request->sku, $request->purchase_token );
            if (!$purchase->isValid()) {
                return response()->json(['status' => 400, 'errors' => ['اطلاعات خرید معتبر نیست']], 200);
                }
        }
        $subscription = new PlanSubscription();
        if($request->market == "myket"){
            $subscription->in_app_purchase_market_type = 'myket';
        }
        else{
            $subscription->in_app_purchase_market_type = 'bazaar';
        }
        $subscription->plan_id = $plan->id;
        $subscription->plan_type = $plan->type;
        $subscription->user_id = $user->id;
        $subscription->price = $plan->price;
        $startDate = PlanSubscriptions::getLastPlanSubscriptionExpirationDate($user , $plan->type);
        if ($startDate) {
            $startDate = Carbon::createFromFormat('Y-m-d', PlanSubscriptions::getLastPlanSubscriptionExpirationDate($user , $plan->type))->addDays(1);
        } else {
            $startDate = Carbon::now();
        }
        $subscription->from_date = $startDate->toDateString();
        $subscription->to_date = $startDate->addMonths($plan->month_inrterval)->toDateString();
        $subscription->pay_id = null;
        $subscription->tracking_code = null;
        $subscription->bazar_in_app_purchase = 1;
        $subscription->purchase_token = $request->purchase_token;
        $subscription->save();
        event(new SubscriptionCreated($subscription));

        return response()->json([
            'status' => 200,
            'message' => 'plan_subscription_has_been_saved',
            'entire' => []
        ]);
    }
    public function buyPlanPaymentGatewayCallback(Request $request)
    {
        try {

            $gateway = \Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $refId = $gateway->refId();
            $user = $request->session()->get('payer');
            $plan = Plan::find($request->session()->get('bought_plan_id'));
            $subscription = new PlanSubscription();
            $subscription->plan_id = $plan->id;
            $subscription->plan_type = $plan->type;
            $subscription->user_id = $user->id;
            $subscription->price = $plan->price;
            $startDate = PlanSubscriptions::getLastPlanSubscriptionExpirationDate($user , $plan->type);
            if ($startDate) {
                $startDate = Carbon::createFromFormat('Y-m-d', PlanSubscriptions::getLastPlanSubscriptionExpirationDate($user , $plan->type))->addDays(1);
            } else {
                $startDate = Carbon::now();
            }
            $subscription->from_date = $startDate->toDateString();
            $subscription->to_date = $startDate->addMonths($plan->month_inrterval)->toDateString();
            $subscription->pay_id = $refId;
            $subscription->tracking_code = $trackingCode;
            $subscription->save();
            event(new SubscriptionCreated($subscription));

            $request->session()->put('gateway_tracking_code', $trackingCode);
            $request->session()->put('cart_payment_date', \jdate()->format('%d %B %Y'));

            return redirect()->route('buy_plan.payment_gateway_finalize');
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function buyPlanPaymentGatewayFinalize(Request $request)
    {
        $plan = Plan::find($request->session()->get('bought_plan_id'));
        return view('app.cart-payment-successful', compact('plan'));
    }

    public function getPlanSubscriptionListOfUsers(){
        $user = auth()->guard('api')->user();
        $planSub = PlanSubscription::join('seller_plans' , 'seller_plans.id' , '=' , 'seller_plan_subscription_details.plan_id')
            ->where('seller_plan_subscription_details.user_id' , '=' , $user->id)
            ->select('seller_plan_subscription_details.*' , 'seller_plans.plan_name' , 'seller_plans.month_inrterval' , 'seller_plans.description')
            ->orderBy('seller_plan_subscription_details.id' , 'desc')
            ->get();
        return response()->json([
            'status' => 200,
            'list' => $planSub
        ]);
    }

}
