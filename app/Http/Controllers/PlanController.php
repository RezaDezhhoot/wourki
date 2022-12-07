<?php

namespace App\Http\Controllers;

use App\Discount;
use App\Events\SubscriptionCreated;
use App\Http\Requests\web\adminPlanRequest;
use App\Http\Requests\web\adminUpdatePlanRequest;
use App\Libraries\Swal;
use App\Plan;
use App\PlanSubscription;
use App\Process\PlanSubscriptions;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\UsedDiscount;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Larabookir\Gateway\Exceptions\InvalidRequestException;
use Larabookir\Gateway\Exceptions\NotFoundTransactionException;
use Larabookir\Gateway\Exceptions\PortNotFoundException;
use Larabookir\Gateway\Exceptions\RetryException;
use Larabookir\Gateway\Gateway;
use Log;
use Throwable;

class PlanController extends Controller
{
    public function list()
    {
        $plans  = Plan::paginate(10);
        return view('admin.plan.index' , compact('plans'));
    }

    public function create(adminPlanRequest $request)
    {
        $plan = new Plan();
        $plan->plan_name        = $request->name;
        $plan->month_inrterval  = $request->time;
        $plan->price            = $request->price;
        $plan->description      = $request->description;
        $plan->status           = $request->status;
        $plan->type             = $request->type;
        $plan->save();
        Swal::success('ثبت موفقیت آمیز.', 'آیتم جدید با موفقیت ثبت شد.');
        return redirect()->back();
    }

    public function deactive(Plan $plan)
    {
        $plan->status = 'hide';
        $plan->save();
        Swal::success('غیرفعالسازی موفقیت آمیز.', 'غیرفعالسازی آیتم با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function active(Plan $plan)
    {
        $plan->status = 'show';
        $plan->save();
        Swal::success('فعالسازی موفقیت آمیز.', 'فعالسازی آیتم با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $plan = Plan::find($request->id);
        $plan->plan_name  = $request->name;
        $plan->month_inrterval  = $request->time;
        $plan->price  = $request->price;
        $plan->description  = $request->description;
        $plan->status  = $request->status;
        $plan->type    = $request->type;
        $plan->save();
        Swal::success('ویرایش موفقیت آمیز.', 'آیتم جدید با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function userCreatePage()
    {
        $user = auth()->guard('web')->user();
        $storeValidatePlan = PlanSubscriptions::storeHasSubscription($user->id);
        $plans = Plan::where('status' , 'show')->get();
        $userWallet = Wallet::where('user_id' , $user->id)->sum('cost');
        return view('frontend.my-account.plans.index' , compact('plans' , 'storeValidatePlan' , 'userWallet'));
    }

    public function userCreate(Request $request)
    {
        $usedDiscount = UsedDiscount::find($request->session()->get('used_discount_id'));
        try {
            $gateway = Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $refId = $gateway->refId();
            if ($usedDiscount) {
                $usedDiscount->status = 'approved';
                $usedDiscount->save();
            }
            $plan_id = $request->session()->get('plan');
            $plan = Plan::find($plan_id);
            $userPlan = auth()->guard('web')->user()->store->plans()->latest()->first();
            if ($userPlan)
                $to_date = Carbon::createFromFormat('Y-m-d' , $userPlan->to_date);
            $userPlanExists = auth()->guard('web')->user()->store->plans()->exists();
            if ($userPlanExists) {
                $userPlan = $userPlan->latest()->first();
                PlanSubscription::create([
                    'plan_id'   => $plan_id ,
                    'user_id'  => auth()->guard('web')->user()->id ,
                    'price'     => $plan->price ,
                    'pay_id'     => $refId ,
                    'plan_type'  => $plan->type,
                    'from_date' => $userPlan->from_date ,
                    'to_date'   => $to_date->addMonths($plan->month_inrterval) ,
                ]);
                
                Swal::success('تبریک.', 'پلن شما با موفقیت تمدید شد.');
                return redirect()->route('edit.store.page');
            } else {
                PlanSubscription::create([
                    'plan_id'   => $plan_id ,
                    'user_id'  => auth()->guard('web')->user()->id ,
                    'price'     => $plan->price ,
                    'pay_id'     => $refId ,
                    'plan_type'  => $plan->type,
                    'from_date' => Carbon::now()->toDateString() ,
                    'to_date'   => Carbon::now()->addMonths($plan->month_inrterval)->toDateString() ,
                ]);
                Swal::success('تبریک.', 'پلن شما مورد نظر با موفقیت برای فروشگاه شما ثبت شد.');
                if (auth()->guard('web')->user()->store->exists())
                    return redirect()->route('edit.store.page');
                else
                    return redirect()->route('create.store.page');
            }
        }
        catch (Throwable $e)
        {
            echo $e->getMessage();
        }

    }

    public function verifyPlan(Request $request)
    {
//        $request->validate([
//           'pay_type' => 'required|in:online,wallet' ,
//           'plan' => 'required|numeric|exists:seller_plans,id'
//        ] , [
//            'pay_type.required' => 'انتخاب یکی از روش های پرداخت الزامی است.',
//            'pay_type.in' => 'روش پرداخت نامعتبر است.',
//            'plan.required' => 'انتخاب پل الزامی است.',
//            'plan.exists' => 'پلن انتخابی نامعتبر است.',
//        ]);
//        $request->validate(['plan' => 'required|exists:seller_plans,id']);
        $plan = Plan::find($request->plan);
        $price = $plan->price;
        $user = User::find(auth()->guard('web')->user()->id);
        //check for discount
        $usedDiscount = null;
        if ($request->has('discount')&& $request->discount) {
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
            if ($price > $user->total_credit) {
                Swal::error('خطا', 'موجودی کیف پول شما کمتر از مبلغ پلن است.');
                return redirect()->back();
            }
            try {
                $commit = \DB::transaction(function () use ($user, $plan , $price , $usedDiscount) {

                    $userPlan = $user->plans()->latest()->first();
                    if ($userPlan)
                        $to_date = Carbon::createFromFormat('Y-m-d', $userPlan->to_date);
                    $userPlanExists = $user->plans()->exists();
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

                    if ($userPlanExists) {
                        $userPlan = $userPlan->latest()->first();
                        $subscription = PlanSubscription::create([
                            'plan_id' => $plan->id,
                            'user_id' => $user->id,
                            'price' => $price,
                            'plan_type'  => $plan->type,
                            'from_date' => $userPlan->from_date,
                            'to_date' => $to_date->addMonths($plan->month_inrterval),
                        ]);
                        if ($usedDiscount) {
                            $usedDiscount->pay_type = 'wallet';
                            $usedDiscount->save();
                        }
                        event(new SubscriptionCreated($subscription));
                        Swal::success('تبریک.', 'پلن شما با موفقیت تمدید شد.');
                        return redirect()->route('edit.store.page');
                    } else {
                        $subscription = PlanSubscription::create([
                            'plan_id' => $plan->id,
                            'user_id' => $user->id,
                            'price' => $price,
                            'plan_type'  => $plan->type,
                            'from_date' => Carbon::now()->toDateString(),
                            'to_date' => Carbon::now()->addMonths($plan->month_inrterval)->toDateString(),
                        ]);
                        if ($usedDiscount) {
                            $usedDiscount->pay_type = 'wallet';
                            $usedDiscount->save();
                        }
                        event(new SubscriptionCreated($subscription));
                        Swal::success('تبریک.', 'پلن شما مورد نظر با موفقیت برای فروشگاه شما ثبت شد.');
                        if (count($user->stores) > 0)
                            return redirect()->route('edit.store.page');
                        else
                            return redirect()->route('create.store.page');
                    }
                });

            } catch (Exception $e) {
                return $e->getTraceAsString();
            }


        } elseif ($request->has('pay_type') && $request->pay_type == 'online' && $price != 0) {
            try {
                $gateway = Gateway::zarinpal();
                $gateway->setCallback(route('user.plan.create'));
                $gateway
                    ->price($price * 10)
                    ->ready();
                if ($usedDiscount) {
                    $usedDiscount->pay_type = 'online';
                    $usedDiscount->status = 'pending';
                    $usedDiscount->save();
                    $request->session()->put('used_discount_id', $usedDiscount->id);
                }
                $request->session()->put('plan', $request->plan);

                return $gateway->redirect();

            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }

        if ($price == 0) {
            $freePlanExists = PlanSubscription::where('price' , 0)->where('plan_id' , $plan->id)
                ->where('user_id' , $user->id)->exists();
            if (!$freePlanExists) {
                $userPlan = User::where('users.id' , $user->id)->first()->plans()->latest()->first();
                if ($userPlan)
                    $to_date = Carbon::createFromFormat('Y-m-d' , $userPlan->to_date);
                $userPlanExists = User::where('users.id' , $user->id)->first()->plans()->exists();
                if ($userPlanExists) {
                    $userPlan = $userPlan->latest()->first();
                    $subscription = PlanSubscription::create([
                        'plan_id'   => $plan->id ,
                        'user_id'  => $user->id ,
                        'price'     => $price ,
                        'plan_type'  => $plan->type,
                        'from_date' => $userPlan->from_date ,
                        'to_date'   => $to_date->addMonths($plan->month_inrterval) ,
                    ]);
                    event(new SubscriptionCreated($subscription));
                    Swal::success('تبریک.', 'پلن شما با موفقیت تمدید شد.');
                    return redirect()->route('edit.store.page');
                } else {
                    $subscription = PlanSubscription::create([
                        'plan_id'   => $plan->id ,
                        'price'     => $price ,
                        'plan_type'  => $plan->type,
                        'from_date' => Carbon::now()->toDateString() ,
                        'to_date'   => Carbon::now()->addMonths($plan->month_inrterval)->toDateString() ,
                    ]);
                    event(new SubscriptionCreated($subscription));
                    Swal::success('تبریک.', 'پلن شما مورد نظر با موفقیت برای فروشگاه شما ثبت شد.');
                    if (count($user->stores) > 0)
                        return redirect()->route('edit.store.page');
                    else
                        return redirect()->route('create.store.page');
                }
            } else {
                Swal::error('خطا!', 'فروشنده گرامی! شما قبلا از پنل رایگان استفاده کرده اید');
                return back();
            }
        }
        return back();
    }

    public function userPlans()
    {
        $user = auth()->guard('web')->user();
        $storeValidatePlan = PlanSubscriptions::storeHasSubscription($user->id);
        $intervalDays = 0;
        $intervalMarketDays = 0;
        $planSubs = PlanSubscription::where('user_id', $user->id)->where('plan_type' , 'store')->latest()->first();
        if ($planSubs) {
            $planSubsFromDate = $planSubs->from_date;
            $planSubsToDate = $planSubs->to_date;
            $now = Carbon::today()->toDateString();
            $minDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsFromDate);
            $maxDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsToDate);
            if ($minDateCarbon->toDateString() <= $now) {
                $intervalDays = $maxDateCarbon->diffInDays(Carbon::today());
            } else {
                $intervalDays = 0;
            }
        }

        $planSubs = PlanSubscription::where('user_id', $user->id)->where('plan_type', 'market')->latest()->first();
        if ($planSubs) {
            $planSubsFromDate = $planSubs->from_date;
            $planSubsToDate = $planSubs->to_date;
            $now = Carbon::today()->toDateString();
            $minDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsFromDate);
            $maxDateCarbon = Carbon::createFromFormat('Y-m-d', $planSubsToDate);
            if ($minDateCarbon->toDateString() <= $now) {
                $intervalMarketDays = $maxDateCarbon->diffInDays(Carbon::today());
            } else {
                $intervalMarketDays = 0;
            }
        }


        $userPlans = null;
        if (count($user->stores)>0) {
            $userPlans = User::where('id' , $user->id)->first()->plans()->latest()->get();
            $userPlans->each(function ($plan) {
                $plan->name = $plan->plan->plan_name;
                $plan->month_interval = $plan->plan->month_inrterval;
            });
        }

        return view('frontend.my-account.plans.bought' , compact('userPlans' , 'intervalDays', 'storeValidatePlan' , 'intervalMarketDays'));
    }

}
