<?php

namespace App\Http\Controllers;

use App\Bill;
use App\BillItem;
use App\Charts\BillChart;
use App\Charts\PlanChart;
use App\Plan;
use App\PlanSubscription;
use App\Product_seller_photo;
use App\ProductSeller;
use App\Store;
use App\Upgrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Morilog\Jalali\Jalalian;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingStoresCount = Store::where('status', 'pending')->count();
        $activeStoresCount = Store::where('status', 'approved')->count();
        $allProductsCount = ProductSeller::count();
        $plansCount = Plan::count();
        $billProductOfThisMonthPrice = BillItem::join('bill', 'bill.id', '=', 'bill_item.bill_id')
            ->where('bill.status', 'delivered')
            ->where('bill_item.created_at', '>=', Carbon::now()->startOfMonth())
            ->where('bill_item.created_at', '<=', Carbon::now()->endOfMonth())
            ->sum(DB::raw('(
                (
                round((bill_item.price - (bill_item.price * bill_item.discount / 100)) * bill_item.quantity)
                )
                +
                IFNULL(
                    (
                    select sum(extra_price)
                    from bill_item_attribute
                    where bill_item_attribute.bill_item_id = bill_item.id
                    )
                , 0)

            )'));
        $totalBillPrice = BillItem::join('bill', 'bill.id', '=', 'bill_item.bill_id')
            ->where('bill.status', 'delivered')
            ->sum(DB::raw('(
                (
                round((bill_item.price - (bill_item.price * bill_item.discount / 100)) * bill_item.quantity)
                )
                +
                IFNULL(
                    (
                    select sum(extra_price)
                    from bill_item_attribute
                    where bill_item_attribute.bill_item_id = bill_item.id
                    )
                , 0)

            )'));
        $billPlanOfThisMonthPrice = 0;
        $price = PlanSubscription::join('seller_plans', 'seller_plans.id', '=', 'seller_plan_subscription_details.plan_id')
            ->sum('seller_plans.price');
        $billPlanOfThisMonthPrice = PlanSubscription::where('seller_plan_subscription_details.created_at', '>=', Carbon::now()->startOfMonth())
            ->where('seller_plan_subscription_details.created_at', '<=', Carbon::now()->endOfMonth())
            ->join('seller_plans', 'seller_plans.id', '=', 'seller_plan_subscription_details.plan_id')
            ->sum('seller_plans.price');
        $totalPlanPrice = $price;
        $fifteenDaysAgoBillsSummary = Bill::where('bill.created_at', '>=', Carbon::now()->addDays(-15)->toDateString())
            ->where('bill.created_at', '<=', Carbon::now()->toDateString())
            ->groupBy('bill.created_at')
            ->select(DB::raw('DATE(bill.created_at) as date'))
            ->get();
        foreach ($fifteenDaysAgoBillsSummary as $index => $date) {
            $billItemsPrice = BillItem::join('bill', 'bill.id', '=', 'bill_item.bill_id')
                ->whereDate('bill.created_at', $date->date)
                ->sum(DB::raw('( bill_item.quantity * ( bill_item.price - ( bill_item.discount / 100 * bill_item.price ) ) )'));

            $billItemAttributePrice = Bill::join('bill_item', 'bill_item.bill_id', '=', 'bill.id')
                ->join('bill_item_attribute', 'bill_item_attribute.bill_item_id', '=', 'bill_item.id')
                ->whereDate('bill.created_at', $date->date)
                ->sum('bill_item_attribute.extra_price');
            $fifteenDaysAgoBillsSummary[$index]->total = $billItemsPrice + $billItemAttributePrice;
            $fifteenDaysAgoBillsSummary[$index]->fa_date = Jalalian::forge($date->date)->format('Y/m/d');
        }
        $billsDate = $fifteenDaysAgoBillsSummary->pluck('fa_date')->toArray();
        $billsPrice = $fifteenDaysAgoBillsSummary->pluck('total')->toArray();
        $billChart = new BillChart();
        $billChart
            ->labels($billsDate)
            ->dataset(
                'میزان فروش محصولات چند روز گذشته',
                'line',
                $billsPrice
            )
            ->options([
                'borderColor' => '#ff0000',
                'legend' => [
                    'display' => true
                ],
            ]);
        $plansSubscriptionDateForDisplayInChart = PlanSubscription::join('seller_plans', 'seller_plans.id', '=', 'seller_plan_subscription_details.plan_id')
            ->select(DB::raw('DATE(seller_plan_subscription_details.created_at) as date'))
            ->whereDate('seller_plan_subscription_details.created_at', '>=', Carbon::now()->addDays(-15)->toDateString())
            ->whereDate('seller_plan_subscription_details.created_at', '<=', Carbon::now()->toDateString())
            ->get();
        foreach ($plansSubscriptionDateForDisplayInChart as $index => $date) {
            $totalPlanBoughtPrice = PlanSubscription::join('seller_plans', 'seller_plans.id', '=', 'seller_plan_subscription_details.plan_id')
                ->whereDate('seller_plan_subscription_details.created_at', $date->date)
                ->sum('seller_plans.price');
            $plansSubscriptionDateForDisplayInChart[$index]->fa_date = Jalalian::forge($date->date)->format('Y/m/d');
            $plansSubscriptionDateForDisplayInChart[$index]->price = $totalPlanBoughtPrice;
        }
        $planSubsPrice = $plansSubscriptionDateForDisplayInChart->pluck('price')->toArray();
        $planSubsDate = $plansSubscriptionDateForDisplayInChart->pluck('fa_date')->toArray();
        $planChart = new PlanChart();
        $planChart
            ->labels($planSubsDate)
            ->dataset(
                'میزان فروش پلن ها در چند روز گذشته',
                'line',
                $planSubsPrice
            )
            ->options([
                'borderColor' => '#2A0569',
                'legend' => [
                    'display' => true
                ],
            ]);
        $hiddenProductsCount = ProductSeller::where('visible' , 0)
            ->where('status' , '!=' , 'deleted')
            ->count();
        $rejectedProductsCount = ProductSeller::where('status' , 'rejected')
            ->count();
        $upgradesCount = Upgrade::query()->count();
        return view('admin.dashboard', compact('hiddenProductsCount' , 'rejectedProductsCount' , 'billChart', 'planChart', 'totalBillPrice', 'totalPlanPrice',
            'pendingStoresCount', 'activeStoresCount', 'plansCount', 'allProductsCount', 'billProductOfThisMonthPrice', 'billPlanOfThisMonthPrice' , 'upgradesCount'));
    }

}
