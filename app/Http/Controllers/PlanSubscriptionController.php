<?php

namespace App\Http\Controllers;

use App\Libraries\Swal;
use App\Plan;
use App\PlanSubscription;
use App\Rules\planSubscriptionRule;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PlanSubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'plan' => new planSubscriptionRule,
            'store' => 'nullable|exists:store,id'
        ], [
            'store.numeric' => 'فروشگاه نامعتبر است.',
            'store.exists' => 'فروشگاه نامعتبر است.',
        ]);

        $stores = Store::where('status', 'approved')->get();
        $plans = Plan::all();
        $planBills = PlanSubscription::join('users', 'users.id', '=', 'seller_plan_subscription_details.user_id')
            ->join('store', 'store.user_id', '=', 'users.id')
            ->join('seller_plans', 'seller_plans.id', '=', 'seller_plan_subscription_details.plan_id')
            ->whereRaw('(seller_plan_subscription_details.id NOT IN (select plan_id from accounting_documents))')
            ->select('seller_plans.plan_name', 'seller_plan_subscription_details.from_date', 'seller_plan_subscription_details.to_date' , 'seller_plan_subscription_details.bazar_in_app_purchase'
                , 'store.name', 'seller_plan_subscription_details.pay_id', 'seller_plan_subscription_details.created_at', 'seller_plan_subscription_details.id',
                'users.first_name' , 'users.last_name' , 'users.mobile');

        if ($request->filled('plan') && $request->plan != 'all') {
            $planBills->where('seller_plans.id', $request->plan);
        }
        if ($request->filled('store') && $request->atore != 'all') {
            $planBills->where('store.id', $request->store);
        }
        if ($request->filled('start_date_ts') && $request->filled('end_date_ts')) {
            $startDate = Carbon::createFromTimestamp($request->start_date_ts)->toDateString();
            $endDate = Carbon::createFromTimestamp($request->end_date_ts)->toDateString();

            $planBills->where('seller_plan_subscription_details.from_date', '>', $startDate)
                ->where('seller_plan_subscription_details.to_date', '<=', $endDate);
        }

        $planBills = $planBills->orderBy('seller_plan_subscription_details.id', 'desc')
            ->paginate(15)->appends([
                'plan' => \request()->input('plan'),
                'store' => \request()->input('store'),
                'start_date_ts' => \request()->input('start_date_ts'),
                'end_date_ts' => \request()->input('end_date_ts'),
            ]);
        return view('admin.plan_subscription.index', compact('plans', 'planBills', 'stores'));
    }

    public function delete(Request $request, PlanSubscription $subscription)
    {
        $subscription->delete();
        Swal::success('حذف اشتراک' , 'پلن اشتراک با موفقیت حذف شد.');
        return redirect()->back();
    }

}
