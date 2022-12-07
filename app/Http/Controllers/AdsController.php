<?php

namespace App\Http\Controllers;

use App\Ads;
use App\AdsPosition;
use App\AdsStairs;
use App\Discount;
use App\Events\AdCreated;
use App\Libraries\Swal;
use App\ProductSeller;
use App\PurchaseProducts\Wallet\WalletHandler;
use App\Rules\ProductIdInSaveNewAdInMyAccount;
use App\Rules\UserMustBeValidAndHasNotStore;
use App\Setting;
use App\Store;
use App\UsedDiscount;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Larabookir\Gateway\Gateway;

class AdsController extends Controller
{
    public function index(Request $request)
    {
        
        $list = AdsPosition::all();
        foreach ($list as $index => $row) {
            $count = Ads::where('ads_position_id', $row->id)->where('status', 'pending')->count();
            $list[$index]->count = $count;
            $list[$index]->pending_num = Ads::where('ads_position_id', $row->id)->where('status', 'pending')->count();
            $list[$index]->approved_num = Ads::where('ads_position_id', $row->id)->where('status', 'approved')->count();
            $list[$index]->rejected_num = Ads::where('ads_position_id', $row->id)->where('status', 'rejected')->count();
        }
        return view('admin.ads-positions.index', compact('list'));
    }

    public function savePositionPrice(Request $request, AdsPosition $position)
    {
        $this->validate($request, [
            'price' => 'required|numeric'
        ]);
        $position->price = $request->price;
        $position->save();

        return redirect()->back();
    }

    public function update(Request $request, Ads $ads)
    {
        $this->validate($request, [
            'position' => 'required|numeric|exists:ads_position,id',
            'final_pic' => 'required|file',
        ], [
            'position.required' => 'انتخاب جایگاه الزامی است.',
            'position.numeric' => 'جایگاه نامعتبر است.',
            'position.exists' => 'جایگاه نامعتبر است.',
            'final_pic.required' => 'تصویر آپلود شده توسط مدیر الزامی است.',
            'final_pic.file' => 'تصویر آپلود شده توسط مدیر نامعتبر است.',
        ]);
        if ($ads->status == 'pending') {
            $expirationInterval = Setting::first()->ads_expire_days;
            $ads->expire_date = Carbon::now()->addDays($expirationInterval)->toDateString();
        }
        $ads->ads_position_id = $request->position;

        $imgName = uniqid() . '.' . $request->final_pic->getClientOriginalExtension();
        $request->final_pic->move(public_path('image/ads'), $imgName);
        $ads->final_pic = $imgName;
        $ads->status = 'approved';
        $ads->save();
        return redirect()->back();
    }

    public function updatePosition(Request $request, Ads $ads)
    {
        $this->validate($request, [
            'position' => 'required|numeric|exists:ads_position,id'
        ], [
            'position.required' => 'انتخاب جایگاه الزامی است.',
            'position.numeric' => 'جایگاه انتخاب شده نامعتبر است.',
            'position.exists' => 'جایگاه انتخاب شده نامعتبر است.',
        ]);
        $ads->update([
            'ads_position_id' => $request->position
        ]);
        return redirect()->back();
    }

    public function changeAdStatus(Request $request, Ads $ads)
    {
        $this->validate($request, [
            'status' => 'required|string|in:pending,approved,rejected'
        ], [
            'status.required' => 'وضعیت الزامی است.',
            'status.string' => 'وضعیت نامعتبر است.',
            'status.in' => 'وضعیت باید یکی از حالات در انتظار تایید، تایید شده یا رد شده باشد.',
        ]);
        $ads->update([
            'status' => $request->status
        ]);
        Swal::success('تغییر وضعیت', 'وضعیت تبلیغ با موفقیت تغییر کرد.');
        return redirect()->back();
    }

    public function adsIndex(Request $request, AdsPosition $position)
    {
        $this->validate($request, [
            'link_type' => 'nullable|string|in:all,store,product',
            'product' => 'nullable|numeric|exists:product_seller,id',
            'store' => 'nullable|numeric|exists:store,id',
            'pay_status' => 'nullable|string|in:all,paid,unpaid',
            'confirmation_status' => 'nullable|string|in:all,pending,approved,rejected',
            'user_name' => [
                'nullable',
                'numeric',
                new UserMustBeValidAndHasNotStore()
            ]
        ], [
            'link_type.string' => 'نوع لینک انتخاب شده نامعتبر است.',
            'link_type.in' => 'نوع لینک انتخاب شده نامعتبر است.',
            'product.numeric' => 'محصول انتخاب شده نامعتبراست.',
            'product.exists' => 'محصول انتخاب شده نامعتبر است.',
            'store.numeric' => 'فروشگاه انتخاب شده نامعتبراست.',
            'store.exists' => 'فروشگاه انتخاب شده نامعتبر است.',
            'pay_status.string' => 'وضعیت پرداخت نامعتبر است.',
            'pay_status.in' => 'وضعیت پرداخت نامعتبر است.',
            'confirmation_status.string' => 'وضعیت تایید نامعتبر است.',
            'confirmation_status.in' => 'وضعیت تایید نامعتبر است.',
            'user_name.numeric' => 'نام کاربر نامعتبر است.',
        ]);
        $list = $position->ads();
        if ($request->filled('link_type') && $request->link_type != 'all') {
            $list->where('ads.link_type', $request->link_type);
            if ($request->link_type == 'store' && $request->filled('store')) {
                $list->where('ads.store_id', $request->store);
            } else if ($request->link_type == 'product' && $request->filled('product')) {
                $list->where('product_id', $request->product);
            }
        } else if ($request->link_type == 'all') {
            if ($request->filled('store')) {
                $list->where('ads.store_id', $request->store);
            } else if ($request->filled('product')) {
                $list->where('product_id', $request->product);
            }
        }
        if ($request->filled('pay_status') && $request->pay_status != 'all') {
            $list->where('pay_status', $request->pay_status);
        }
        if ($request->filled('confirmation_status') && $request->confirmation_status != 'all') {
            $list->where('status', $request->confirmation_status);
        }
        if ($request->filled('position') && $request->position != 'all') {
            $list->where('ads_position_id', $request->position);
        }
        if($request->filled('user_name')){
            $list->where('user_id' , $request->user_name);
        }
        $list = $list->with(['payments', 'user' => function($query){
            $query->select('users.id' , 'users.first_name' , 'users.last_name')
                ->addSelect(\DB::raw('(
                    select sum(wallet.cost)
                    from wallet 
                    where wallet.user_id = users.id
                ) as wallet_stock'));
        }])->get();
        $positions = AdsPosition::all();
        if ($request->filled('product')) {
            $currentProduct = ProductSeller::find($request->product);
        } else {
            $currentProduct = null;
        }
        if ($request->filled('store')) {
            $currentStore = Store::find($request->store);
        } else {
            $currentStore = null;
        }

        return view('admin.ads.index', compact('list', 'position', 'positions', 'currentProduct', 'currentStore'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'position' => 'required|numeric|exists:ads_position,id',
            'pic' => 'required|file|max:5120',
            'user' => 'required|numeric|exists:users,id',
            'link_to' => 'required|string|in:store,product',
            'product_id' => 'required_if:link_to,product|numeric|exists:product_seller,id',
            'store_type' => 'required_if:link_to,store|in:product,service,market',
            'price' => 'required_unless:default_price,|numeric|min:0',
            'expire_date_ts' => 'nullable|numeric',
            'status' => 'required|string|in:pending,approved,rejected',
            'description' => 'required|string',

        ], [
            'position.required' => 'انتخاب جایگاه الزامی است.',
            'position.numeric' => 'جایگاه نامعتبر است.',
            'position.exists' => 'جایگاه نامعتبر است.',
            'pic.required' => 'انتخاب تصویر الزامی است.',
            'pic.file' => 'تصویر نامعتبر است.',
            'pic.max' => 'تصویر حداکثر می تواند 5 مگابایت بذشد.',
            'user.required' => 'نام کاربر الزامی است.',
            'user.numeric' => 'کاربر انتخاب شده نامعتبر است.',
            'user.exists' => 'کاربر انتخاب شده نامعتبر است.',
            'link_to.required' => 'این فیلد الزامی است.',
            'link_to.string' => 'این فیلد نامعتبر است.',
            'link_to.in' => 'این فیلد تنها می تواند یکی از مقادیر فروشگاه یا محصول را داشته باشد.',
            'product_id.required_if' => 'انتخاب محصول الزامی است.',
            'product_id.numeric' => 'محصول انتخاب شده نامعتبر است.',
            'product_id.exists' => 'محصول انتخاب شده نامعتبر است.',
            'price.required_unless' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت باید به صورت عددی وارد شود.',
            'price.min' => 'قیمت حداقل باید صفر باشد.',
            'store_type.in' => 'نوع فروشگاه را به درستی وارد نمایید',
            'store_type.required_if' => 'نوع فروشاه الزامی است',
            'expire_date_ts.numeric' => 'تاریخ انتخاب شده ناغمعتبر است.',
            'status.required' => 'انتخاب وضعیت الزامی است.',
            'status.string' => 'وضعیت نامعتبر است.',
            'status.in' => 'وضعیت تنها می تواند یکی از مقادیر در انتظار تایید، تایید شده یا رد شده را داشته باشد.',
            'description.required' => 'توضیحات الزامی است.',
            'description.string' => 'توضیحات نامعتبر است.',
        ]);

        $position = AdsPosition::find($request->position);
        $user = User::find($request->user);
        if ($request->link_to == 'store' && count($user->stores) == 0) {
            Swal::error('خطا', 'کاربر انتخاب شده دارای فروشگاه نیست.');
            return redirect()->back();
        }
        $ads = new Ads();
        $ads->ads_position_id = $position->id;

        $imgName = uniqid() . '.' . $request->pic->getClientOriginalExtension();
        $request->pic->move(public_path('/image/ads'), $imgName);
        $ads->pic = $imgName;
        $ads->final_pic = $imgName;

        $ads->user_id = $request->user;
        $ads->link_type = $request->link_to;
        if ($request->link_to == 'store') {
            $ads->product_id = null;
            $ads->store_id = optional(Store::where('user_id', $user->id)->where('store_type', $request->store_type)->first())->id;
        } else {
            $ads->product_id = $request->product_id;
            $ads->store_id = null;
        }

        $ads->description = $request->description;
        $ads->pay_status = 'paid';
        $ads->payment_type = 'wallet';
        $ads->status = $request->status;
        if ($request->has('expire_date_based_on_default_setting')) {
            $expireDaysInterval = Setting::first()->ads_expire_days;
            $ads->expire_date = Carbon::now()->addDays($expireDaysInterval)->toDateString();
        } else {
            $expireTsDate = Carbon::createFromTimestamp($request->expire_date_ts / 1000)->toDateString();
            $ads->expire_date = $expireTsDate;
        }
        $ads->save();
        if ($request->has('default_price')) {
            $paymentPrice = $position->price;
        } else {
            $paymentPrice = $request->price;
        }

        $payment = new AdsStairs();
        $payment->ads_id = $ads->id;
        $payment->payment_type = 'wallet';
        $payment->pay_date = Carbon::now()->toDateString();
        $payment->initial_pay = 'initial';
        $payment->price = $paymentPrice;
        $payment->save();

        if ($request->has('pay_from_user_wallet')) {
            Wallet::create([
                'user_id' => $user->id,
                'cost' => $paymentPrice,
                'wallet_type' => 'buy_ad'
            ]);
            //todo : check if price is negative to add wallet reduce
        }
        Swal::success('ثبت تبلیغ', 'تبلیغ شما با موفقیت ثبت شد.');
        return redirect()->route('ads_list_management', $position->id);
    }

    public function show(Ads $ads)
    {
        $positions = AdsPosition::all();
        return view('admin.ads.show', compact('ads', 'positions'));
    }

    public function createInMyAccount()
    {
        $positions = AdsPosition::all();
        $user = auth()->guard('web')->user();
        if (count($user->stores) == 0) {
            abort(404);
        }
        $products = Store::where('user_id' , $user->id)->where('store_type' , 'product')->join('product_seller' , 'store.id' , '=' , 'product_seller.store_id')
        ->where('product_seller.status' , '=' , 'approved')
        ->select('product_seller.id as id' , 'product_seller.name as name')->get();
        $services = Store::where('user_id' , $user->id)->where('store_type' , 'service')->join('product_seller', 'store.id', '=', 'product_seller.store_id')
        ->where('product_seller.status', '=', 'approved')
        ->select('product_seller.id as id', 'product_seller.name as name')->get();
        $walletStock = Wallet::where('user_id', $user->id)->sum('cost');
        $helpText = Setting::first()->ads_page_help_text;
        $adsList = Ads::whereIn('status', ['approved', 'pending'])
            ->join('ads_position', 'ads_position.id', '=', 'ads.ads_position_id')
            ->where('pay_status', 'paid')
            ->select('ads.*', 'ads_position.name as position_name', 'ads_position.id as position_id', 'ads_position.price as position_price')
            ->where('ads.user_id', $user->id)
            ->orderByDesc('ads.updated_at')
            ->get();
        foreach ($adsList as $index => $ad) {
            $extend = AdsStairs::where('ads_id', $ad->id)
                ->orderBy('created_at', 'desc')
                ->first();
            $adsList[$index]->extend = $extend;
        }
        return view('frontend.my-account.ads.create', compact('adsList', 'products','services', 'positions', 'walletStock', 'helpText'));
    }

    public function storeInMyAccount(Request $request)
    {
        // validate input data
        $validator = Validator::make($request->all(), [
            'position' => 'required|numeric|exists:ads_position,id',
//            'ads_photo' => 'required|file|max:10240',
            'description' => 'required|string|min:5',
            'link_to' => 'required|string|in:store,product',
            'store_type' => 'required_if:link_to,store|string|in:product,service',
            'product_name' => new ProductIdInSaveNewAdInMyAccount(),
            'action' => 'required|in:gateway,wallet',
            'discount' => 'nullable|exists:discounts,id'
        ], [
            'position.required' => 'جایگاه الزامی است.',
            'position.numeric' => 'جایگاه نامعتبر است.',
            'position.exists' => 'جایگاه نامعتبر است.',
            //            'ads_photo.required' => 'تصویر الزامی است.',
            //            'ads_photo.file' => 'تصویر نامعتبر است.',
            //            'ads_photo.max' => 'تصویر حداکثر می تواند 10 مگابایت باشد.',
            'store_type.in' => 'نوع فروشگاه را به درستی وارد نمایید',
            'store_type.required_if' => 'نوع فروشاه الزامی است',
            'description.required' => 'توضیحات الزامی است.',
            'description.string' => 'توضیحات نامعتبر است.',
            'description.min' => 'توضیحات باید حداقل 5 کاراکتر باشد.',
            'link_to.required' => 'این فیلد الزامی است.',
            'link_to.string' => 'این فیلد نامعتبر است.',
            'link_to.in' => 'این فیلد باید یکی از مقادیر محصول یا فروشگاه را داشته باشد.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $position = AdsPosition::find($request->position);
        $user = auth()->guard('web')->user();
        if ($request->action == 'wallet' && $user->wallet()->sum('cost') < $position->price) {
            Swal::error('خطا', 'موجودی کیف پول شما کمتر از مبلغ تبلیغ مورد نظر است.');
            return redirect()->back();
        }
        //save ad in database
        $ads = new Ads();
        $ads->ads_position_id = $request->position;

//        $imgName = uniqid() . '.' . $request->ads_photo->getClientOriginalExtension();
//        $request->ads_photo->move(public_path('image/ads'), $imgName);
//        $ads->pic = $imgName;

        $ads->link_type = $request->link_to;
        if ($request->link_to == 'product') {
            $ads->product_id = $request->product_name;
            $ads->store_id = null;
        } else {
            $ads->store_id = optional(Store::where('user_id', $user->id)->where('store_type', $request->store_type)->first())->id;
            $ads->product_id = null;
        }
        $ads->description = $request->description;

        $ads->status = 'pending';
        if($request->action == 'wallet') {
            $ads->payment_type = 'wallet';
            $ads->pay_status = 'paid';
        }
        else{
            $ads->payment_type = 'online';
            $ads->pay_status = 'unpaid';
        }
//        $setting = Setting::first();
//        $expireDaysInterval = $setting->ads_expire_days;
//        $ads->expire_date = Carbon::now()->addDays($expireDaysInterval);
        $ads->user_id = $user->id;
        $ads->save();

        $payment = new AdsStairs();
        $payment->ads_id = $ads->id;
        $payment->pay_date = Carbon::now()->toDateString();
        $payment->initial_pay = 'initial';

        $payment->price = $position->price;
        //check for discount
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount && $request->discount != "") {
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
        if($request->action == 'wallet'){
            $payment->payment_type = 'wallet';
        }
        else{
            $payment->payment_type = 'online';
        }
        $payment->save();

        if($request->action == 'wallet'){
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'cost' => -1 * $payment->price,
                'wallet_type' => 'buy_ad'
            ]);
            event(new AdCreated($ads , $payment->price));
            //wallet reduce
            $walletHandler = new WalletHandler();
            if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
                $wallet->reducedItem()->attach($data);
            }
            if($usedDiscount){
                $usedDiscount->pay_type = 'wallet';
                $usedDiscount->save();
            }
            Swal::success('ثبت تبلیغ', 'تبلیغ با موفقیت ثبت شد.');
            return redirect()->back();
        }
        else{
            //sending user to zarinpal
            $request->session()->put('cost', $payment->price);
            $request->session()->put('paying_ad_id' , $payment->id);
            $gateway = Gateway::zarinpal();
            $gateway->setCallback(route('verify.onlinepay'));
            $gateway
                ->price($payment->price * 10)
                ->ready();
            if ($usedDiscount) {
                $usedDiscount->pay_type = 'online';
$usedDiscount->status = 'pending';
                $usedDiscount->save();
                $request->session()->put('used_discount_id' , $usedDiscount->id);
            }
            return $gateway->redirect();
        }
    }
    public function verifyOnlinepay(Request $request)
    {
        try {
            $usedDiscount = UsedDiscount::find($request->session()->get('used_discount_id'));
            \DB::beginTransaction();
            $payment = AdsStairs::find($request->session()->get('paying_ad_id'));
            if(!$payment){
                return 'مشکلی در پرداخت شما به وجود آمده است';
            }
            $ad = Ads::find($payment->ads_id);
            $ad->pay_status = 'paid';
            $ad->save();
            event(new AdCreated($ad , $payment->price));
            $gateway = Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $payment->tracking_code = $trackingCode;
            if($usedDiscount){
                $usedDiscount->status = 'approved';
                $usedDiscount->save();
            }
            \DB::commit();
            Swal::success('تبریک!', 'پرداخت شما با موفقیت انجام شد');
            return redirect()->route('my_account.ads_panel');
        }  catch (\Exception $e) {
            \DB::rollBack();
            return $e->getMessage();
        }
    }

    public function extendsAd(Request $request, Ads $ads)
    {
        // validate input data
        $validator = Validator::make($request->all(), [
            'description' => 'required|string|min:5',
            // 'link_to' => 'required|string|in:store,product',
            // 'store_type' => 'required_if:link_to,store|in:product,service',
            // 'product_name' => new ProductIdInSaveNewAdInMyAccount(),
            'discount' => 'nullable|exists:discounts,id'
        ], [
            'description.required' => 'توضیحات الزامی است.',
            'description.string' => 'توضیحات نامعتبر است.',
            'description.min' => 'توضیحات باید حداقل 5 کاراکتر باشد.',
            // 'link_to.required' => 'این فیلد الزامی است.',
            // 'link_to.string' => 'این فیلد نامعتبر است.',
            // 'link_to.in' => 'این فیلد باید یکی از مقادیر محصول یا فروشگاه را داشته باشد.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $position = $ads->position;

        $user = auth()->guard('web')->user();
        //check for discount
        $usedDiscount = null;
        if ($request->has('discount') && $request->discount && $request->discount != "") {
            $discount = Discount::getDiscountFor(Discount::find($request->discount)->code, 'ad', $position->id);
            if (!is_null($discount)) {
                $usedDiscount = new UsedDiscount();
                $usedDiscount->user_id = $user->id;
                $usedDiscount->discount_id = $discount->id;
                $usedDiscount->price = $position->price;
                $usedDiscount->pay_type = 'wallet';
                $position->price = $discount->applyOn($position->price);
                $usedDiscount->price_with_discount = $position->price;
                $usedDiscount->save();
            }
        }
        if ($user->wallet()->sum('cost') < $position->price) {
            Swal::error('خطا', 'موجودی کیف پول شما کمتر از مبلغ تبلیغ مورد نظر است.');
            return redirect()->back();
        }

        $extend = new AdsStairs();
        $extend->ads_id = $ads->id;
        $extend->payment_type = 'wallet';
        $extend->pay_date = Carbon::now()->toDateString();
        $extend->initial_pay = 'stairs';
        $extend->price = $position->price;
        $extend->save();

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'cost' => -1 * $extend->price,
            'wallet_type' => 'buy_ad'
        ]);

        //wallet reduce
        $walletHandler = new WalletHandler();
        if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
            $wallet->reducedItem()->attach($data);
        }

        // if ($request->hasFile('ads_photo')) {
        //     $imgName = uniqid() . '.' . $request->ads_photo->getClientOriginalExtension();
        //     $request->ads_photo->move(public_path('image/ads'), $imgName);
        //     $ads->pic = $imgName;
        // }
        // $ads->link_type = $request->link_to;
        // if ($request->link_to == 'product') {
        //     $ads->product_id = $request->product_name;
        //     $ads->store_id = null;
        // } else {
        //     $ads->store_id = optional(Store::where('user_id' , $user->id)->where('store_type' , $request->store_type)->first())->id;
        //     $ads->product_id = null;
        // }
        $ads->description = $request->description;
        $ads->pay_status = 'paid';
        $ads->payment_type = 'wallet';
        $ads->status = 'pending';

        $daysInterval = Setting::first()->ads_expire_days;
        $ads->expire_date = Carbon::now()->addDays($daysInterval)->toDateString();
        $ads->save();

        Swal::success('ثبت تبلیغ', 'تبلیغ ثبت شده در انتظار تایید مدیریت سایت قرار گرفت و پس از تایید در سایت نمایش داده خواهد شد.');
        return redirect()->back();
    }

    public function adminCreate(Request $request)
    {
        $positions = AdsPosition::all();
        return view('admin.ads.create', compact('positions'));
    }

    public function delete(Request $request, Ads $ads)
    {
        $ads->delete();
        return redirect()->back();
    }

    public function userAds(Request $request, User $user)
    {

        $list = $user->ads()->with(['payments', 'position'])->get();
        $positions = AdsPosition::all();
        return view('admin.ads.user.index', compact('user', 'list', 'positions'));
    }

    public function updateAdInMyAccount(Request $request, Ads $ads)
    {
        $this->validate($request, [
            'ads_photo' => 'required|file',
            'link_to' => 'required|string|in:store,product',
            'product_name' => 'required_if:link_to,product|numeric|exists:product_seller,id',
            'product_name' => 'required_if:link_to,product|numeric|exists:product_seller,id',
            'description' => 'required|string'
        ], [
            'ads_photo.required' => 'انتخاب تصویر الزامی است.',
            'ads_photo.file' => 'تصویر نامعتبر است.',
            'link_to.required' => 'لینک به الزامی است.',
            'link_to.string' => 'لینک به نامعتبر است.',
            'link_to.in' => 'لینک به تنها می تواند یکی از مقادیر محصول یا فروشگاه را داشته باشد.',
            'product_name.required_if' => 'در صورت انتخاب محصول به عنوان لینک باید محصول انتخاب شود.',
            'product_name.numeric' => 'محصول نامعتبر است.',
            'product_name.exists' => 'محصول نامعتبر است.',
            'description.required' => 'توضیحات الزامی است.',
            'description.string' => 'توضیحات نامعتبر است.',
        ]);
        $picName = uniqid() . '.' . $request->ads_photo->getClientOriginalExtension();
        $request->ads_photo->move(public_path('image/ads'), $picName);



        $ads->update([
            'pic' => $picName,
            'link_type' => $request->link_to,
            'product_id' => $request->product_name,
            'description' => $request->description,
            'status' => 'pending'
        ]);
        Swal::success('ویرایش تبلیغ' , 'تبلیغ شما با موفقیت ویرایش شد و پس از تایید مدیریت سایت نمایش داده خواهد شد.');
        return redirect()->back();
    }
    public function deleteAdInMyAccount(Request $request, Ads $ads){
        //deleting ad
        $ads->delete();
        Swal::success('حذف تبلیغ' , 'تبلیغ شما با موفقیت حذف شد.');
        return redirect()->back();
    }
    public function payAdsCostManuallyWithWallet(Request $request , Ads $ads){
        $this->validate($request , [
            'user_id' => 'required|numeric|exists:users,id',
        ] , [
            'user_id.required' => 'کاربر نامعتبر است.',
            'user_id.numeric' => 'کاربر نامعتبر است.',
            'user_id.exists' => 'کاربر نامعتبراست.',
        ]);
        $user = User::find($request->user_id);
        $walletStock = Wallet::where('user_id' , $user->id)
            ->sum('cost');
        if($walletStock < $ads->position->price){
            Swal::error('خطا' , 'موجودی کیف پول کمتر از هزینه آگهی است.');
            return redirect()->back();
        }
        $ads->payments()->create([
            'payment_type' => 'wallet',
            'pay_date' => Carbon::now()->toDateString(),
            'initial_pay' => $ads->payments()->count() > 0 ? 'stairs' : 'initial',
            'price' => $ads->position->price,
        ]);
        $ads->pay_status = 'paid';
        $ads->save();

        $wallet = Wallet::create([
            'user_id' => $ads->user_id,
            'cost' => -1 * $ads->position->price,
            'wallet_type' => 'buy_ad'
        ]);
        //wallet reduce
        $walletHandler = new WalletHandler();
        if ($data = $walletHandler->NegativeRecordReducer($wallet)) {
            $wallet->reducedItem()->attach($data);
        }
        Swal::success('پرداخت موفقیت آمیز', 'هزینه تبلیغ از کیف پول کاربر کسر گردید.');
        return redirect()->back();
    }
}
