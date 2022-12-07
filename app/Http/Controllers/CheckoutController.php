<?php

namespace App\Http\Controllers;

use App\Checkout;
use App\Events\CheckoutStore;
use App\Libraries\Swal;
use App\Marketer;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Morilog\Jalali\Jalalian;

class CheckoutController extends Controller
{
    public function create()
    {
        $stores = Store::where('status' , 'approved')
            ->orderBy('name' , 'asc')
            ->get();
        return view('admin.checkout.create' , compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'store' => 'required|numeric|exists:store,id',
            'price' => 'required|numeric',
            'pay_id' => 'required',
            'date' => 'required|date_format:d/m/Y',
        ], [
            'store.required' => 'انتخاب فروشگاه الزامی است.',
            'store.numeric' => 'فروشگاه نامعتبر است.',
            'store.exists' => 'فروشگاه نامعتبر است.',
            'pay_id.required' => 'شماره پیگیری الزامی است.',
            'price.required' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت نامعتبر است.',
            'date.required' => 'انتخاب تاریخ الزامی است.',
            'date.date_format' => 'فرمت تاریخ نامعتبر است.',
        ]);
        $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('d/m/Y', $request->date)->format('Y-m-d');
        $checkout = new Checkout();
        $checkout->store_id = $request->store;
        $checkout->price = $request->price;
        $checkout->pay_id = $request->pay_id;
        $checkout->created_at = $date;
        $checkout->updated_at = $date;
        $checkout->save();

        event(new CheckoutStore(Store::find($request->store)));
        Swal::success('موفقیت آمیز بودن تسویه حساب.', 'تسویه حساب با موفقیت ایجاد شد.');
        return redirect()->route('checkout.index');
    }

    public function storeCheckout(Request $request)
    {
        $request->validate([
            'store' => 'nullable|numeric|exists:store,id'
        ] , [
            'store.numeric' => 'فروشگاه نامعتبر است.',
            'store.exists' => 'فروشگاه نامعتبر است.',
        ]);
        $stores = Store::where('status' , 'approved')
            ->orderBy('name' , 'asc')
            ->get();
        $checkouts = Checkout::join('store' , 'store.id' , 'checkouts.store_id')
            ->select('checkouts.*' , 'store.name' , 'store.id as store_id')
            ->where('checkouts.store_id', '!=', null)
            ->whereRaw('( checkouts.id NOT IN(select checkout_id from accounting_documents) )');

        if ($request->filled('store') && $request->atore != 'all'){
            $checkouts->where('store.id' , $request->store);
        }
        $checkouts = $checkouts
            ->orderBy('id' , 'desc')
            ->paginate(20);

        return view('admin.checkout.store-checkout' , compact('stores' , 'checkouts'));
    }

    public function marketerCheckout(Request $request)
    {
        $request->validate([
            'marketer' => 'nullable|numeric|exists:marketer,id'
        ] , [
            'marketer.numeric' => 'فروشگاه نامعتبر است.',
            'marketer.exists' => 'فروشگاه نامعتبر است.',
        ]);
        $marketers = Marketer::select('marketer.*')
            ->addSelect(DB::raw('(
                select concat(first_name, " " , last_name)
                from users
                where users.id = marketer.user_id
            ) as full_name'))
            ->get();
        $checkouts = Checkout::join('marketer' , 'marketer.user_id' , 'checkouts.marketer_id')
            ->join('users', 'users.id', '=', 'marketer.user_id')
            ->select('checkouts.*' , 'users.first_name', 'users.last_name' , 'marketer.id as marketer_id')
            ->where('checkouts.marketer_id', '!=', null)
            ->whereRaw('( checkouts.id NOT IN(select marketer_id from accounting_documents) )');
        if ($request->filled('marketer')){
            $checkouts->where('marketer.id' , $request->marketer);
        }
        $checkouts = $checkouts
            ->orderBy('id' , 'desc')
            ->paginate(20);
        return view('admin.checkout.marketer-checkout' , compact('marketers' , 'checkouts'));
    }

    public function storeCheckoutUpdate(Request $request , $checkout)
    {
        $request->validate([
           'store' => 'required|numeric|exists:store,id',
           'price' => 'required|numeric',
           'pay_id' => 'required',
            'date' => 'required|date|date_format:Y/m/d',
        ], [
            'store.required' => 'انتخاب فروشگاه الزامی است.',
            'store.numeric' => 'فروشگاه نامعتبر است.',
            'store.exists' => 'فروشگاه نامعتبر است.',
            'price.required' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت نامعتبر است.',
            'date.required' => 'انتخاب تاریخ الزامی است.',
            'date.date_format' => 'فرمت تاریخ نامعتبر است.',
        ]);
        $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $request->date)->format('Y-m-d');
        $checkout = Checkout::find($checkout);
        $checkout->store_id = $request->store;
        $checkout->price = $request->price;
        $checkout->pay_id = $request->pay_id;
        $checkout->created_at = $date;
        $checkout->save();
        Swal::success('موفقیت آمیز.', 'تسویه حساب مورد نظر با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function marketerCheckoutUpdate(Request $request , $checkout)
    {
        $request->validate([
            'marketer' => 'required|numeric|exists:marketer,id',
            'price' => 'required|numeric',
            'pay_id' => 'required',
            'date' => 'required|date|date_format:Y/m/d',
        ], [
            'store.required' => 'انتخاب فروشگاه الزامی است.',
            'store.numeric' => 'فروشگاه نامعتبر است.',
            'store.exists' => 'فروشگاه نامعتبر است.',
            'price.required' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت نامعتبر است.',
            'date.required' => 'انتخاب تاریخ الزامی است.',
            'date.date_format' => 'فرمت تاریخ نامعتبر است.',
        ]);
        $date = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $request->date)->format('Y-m-d');
        $checkout = Checkout::find($checkout);
        $checkout->marketer_id = $request->marketer;
        $checkout->price = $request->price;
        $checkout->pay_id = $request->pay_id;
        $checkout->created_at = $date;
        $checkout->save();
        Swal::success('موفقیت آمیز.', 'تسویه حساب مورد نظر با موفقیت ویرایش شد.');
        return redirect()->back();
    }
}
