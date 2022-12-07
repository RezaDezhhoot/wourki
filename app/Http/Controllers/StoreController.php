<?php

namespace App\Http\Controllers;

use App\Address;
use App\Bill;
use App\BillItem;
use App\Category;
use App\City;
use App\Discount;
use App\Events\ApproveStore;
use App\Events\RejectStore;
use App\Events\StoreCreated;
use App\Exports\StoresMobileExport;
use App\Exports\UsersMobileExport;
use App\Guild;
use App\Helpers\RawQueries;
use App\Http\Requests\web\filterStoreRequest;
use App\Http\Requests\web\StoreRequest;
use App\Http\Requests\web\UpdateRequest;
use App\Http\Requests\web\updateStoreRequest;
use App\Http\Requests\web\uploadPhotoRequest;
use App\Libraries\Swal;
use App\MarketCommission;
use App\Marketer;
use App\MarketProduct;
use App\Message;
use App\Plan;
use App\PlanSubscription;
use App\Process\PlanSubscriptions;
use App\Product_seller_photo;
use App\ProductPhoto;
use App\ProductSeller;
use App\ProductSellerComment;
use App\Province;
use App\Rate;
use App\ReagentCode;
use App\Report;
use App\Rules\CategoryRule;
use App\Setting;
use App\Store;
use App\Store_photo;
use App\UpgradePosition;
use App\User;
use App\Wallet;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class StoreController extends Controller
{
    public function index(filterStoreRequest $request)
    {
        $provinces = Province::all();
        $guilds = Guild::all();
        $storeLists = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('users' , 'users.id' , '=' , 'store.user_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->leftJoin('reagent_code', 'reagent_code.user_id', '=', 'users.id')
            ->leftJoin('users as referrer_user', 'referrer_user.mobile', '=', 'reagent_code.reagent_code')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            // ->where('store.status', '!=', 'pending')
            ->select('users.first_name', 'users.last_name', 'users.id as user_id', 'store.name as store_name', 'guild.name as guild_name', 'province.name as province_name',
                'address.address', 'store.visible', 'store.min_pay', 'store.pay_type', 'store.activity_type', 'city.name as city_name',
                'store.status', 'users.mobile', 'store.phone_number', 'store.total_hits', 'store.id', 'store.user_name', 'users.thumbnail_photo', 'store.created_at', 'store.slogan' , 'store.store_type')
            ->addSelect('referrer_user.first_name as referrer_first_name')
            ->addSelect('referrer_user.last_name as referrer_last_name')
            ->addSelect(DB::raw('(
                select count(*)
                from product_seller
                where product_seller.store_id = store.id and
                product_seller.status != "deleted"
            ) as productsCount'))
            ->groupBy('users.first_name', 'users.last_name', 'users.id', 'store.name', 'guild.name', 'province.name',
                'address.address', 'store.visible', 'store.min_pay', 'store.pay_type', 'store.activity_type', 'city.name',
                'store.status', 'users.mobile', 'store.phone_number', 'store.total_hits', 'store.id', 'store.user_name', 'users.thumbnail_photo', 'store.created_at',
                'referrer_user.first_name', 'referrer_user.last_name', 'store.slogan');
        if ($request->has('storeProductCount')) {
            $storeLists->whereRaw('(
                (
                select count(*) 
                from product_seller
                where product_seller.store_id = store.id              
                ) = 0
            )');
        }
        if ($request->filled('subscription')) {
            if ($request->subscription == 1) {
                $storeLists->whereRaw('(
                    (
                    select count(*) 
                    from seller_plan_subscription_details sub
                    where sub.from_date <= "' . Carbon::now()->toDateString() . '"
                    and sub.to_date >= "' . Carbon::now()->toDateString() . '"
                    and sub.store_id = store.id
                )  > 0
                )');
            } else {
                $storeLists->whereRaw('(
                    (
                    select count(*) 
                    from seller_plan_subscription_details sub
                    where sub.from_date <= "' . Carbon::now()->toDateString() . '"
                    and sub.to_date >= "' . Carbon::now()->toDateString() . '"
                    and sub.store_id = store.id
                )  = 0 
                )');
            }
        }
        if ($request->filled('pay_type')) {
            $storeLists->where('store.pay_type', $request->pay_type);
        }
        if ($request->filled('activity_type')) {
            $storeLists->where('store.activity_type', $request->activity_type);
        }
        if ($request->filled('province')) {
            $storeLists->where('province.id', $request->province);
        }
        if ($request->filled('city')) {
            $storeLists->where('city.id', $request->city);
        }
        if ($request->filled('guild')) {
            $storeLists->where('guild.id', $request->guild);
        }
        if ($request->filled('visibility')) {
            $storeLists->where('store.visible', $request->visibility);
        }
        if ($request->filled('status')) {
            $storeLists->where('store.status', $request->status);
        }
        if ($request->filled('store_type')) {
            $storeLists->where('store.store_type', $request->store_type);
        }
        if ($request->has('store_name')) {
            $storeLists->where('store.name', 'like', '%' . $request->store_name . '%');
        }
        if ($request->filled('user_mobile')) {
            $storeLists->where('users.mobile', 'like', "%" . $request->user_mobile . "%");
        }
        if ($request->filled('name_of_user')) {
            $storeLists->where(function ($nameQuery) use ($request) {
                $nameQuery->where('users.first_name', 'like', "%" . $request->name_of_user . "%")
                    ->orWhere('users.last_name', 'like', "%" . $request->name_of_user . "%");
            });
        }
        if ($request->filled('store_user_name')) {
            $storeLists->where('store.user_name', 'like', "%" . $request->store_user_name . "%");
        }
        $storeLists = $storeLists->orderBy('store.id', 'desc')
            ->paginate(15)
            ->appends([
                'storeProductCount' => $request->storeProductCount,
                'pay_type' => $request->pay_type,
                'activity_type' => $request->activity_type,
                'province' => $request->province,
                'city' => $request->city,
                'guild' => $request->guild,
                'visibility' => $request->visibility,
                'status' => $request->status,
                'store_name' => $request->store_name,
            ]);

        foreach ($storeLists as $index => $row) {
            $storeSubscription = PlanSubscriptions::storeHasSubscription($row->user_id);
            if ($storeSubscription == true)
                $storeLists[$index]->status_subscription = 1;
            else
                $storeLists[$index]->status_subscription = 0;

            $products = ProductSeller::where('status', '!=', 'deleted')
                ->where('store_id', $row->id)
                ->get();
            $storeLists[$index]->products = $products;

            $photos = Store_photo::where('store_id', $row->id)->get();
            $storeLists[$index]->photos = $photos;

            $messages = Message::where(function ($subQuery) use ($row) {
                $subQuery->where('user_id', '=', $row->user_id)
                    ->orWhere('receiver_id', '=', $row->user_id);
            })
                ->orderBy('message.created_at', 'desc')
                ->get();
            $storeLists[$index]->messages = $messages;
        }
        $numOfStores = Store::count();
        $defaultExcelExportRowsLimit = Setting::first()->excel_export_rows_num;
        $positions = UpgradePosition::all();
        return view('admin.store.index', compact('storeLists', 'provinces', 'guilds' , 'numOfStores' , 'defaultExcelExportRowsLimit' , 'positions'));
    }

    public function PendingList(filterStoreRequest $request)
    {
        $provinces = Province::all();
        $guilds = Guild::all();
        $storeLists = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->where('store.status', 'pending')
            ->select('users.id as user_id', 'users.first_name', 'users.last_name', 'store.name as store_name', 'guild.name as guild_name', 'province.name as province_name', 'city.name as city_name',
                'address.address', 'store.visible', 'store.min_pay', 'store.pay_type', 'store.activity_type',
                'store.status', 'users.mobile', 'store.phone_number', 'store.total_hits', 'store.id', 'store.user_name', 'store.slogan' ,'store.store_type');

        if ($request->filled('pay_type')) {
            $storeLists->where('store.pay_type', $request->pay_type);
        }
        if ($request->filled('activity_type')) {
            $storeLists->where('store.activity_type', $request->activity_type);
        }
        if ($request->filled('province')) {
            $storeLists->where('province.id', $request->province);
        }
        if ($request->filled('city')) {
            $storeLists->where('city.id', $request->city);
        }
        if ($request->filled('guild')) {
            $storeLists->where('guild.id', $request->guild);
        }
        if ($request->filled('visibility')) {
            $storeLists->where('store.visible', $request->visibility);
        }
        if ($request->has('store_name')) {
            $storeLists->where('store.name', 'like', '%' . $request->store_name . '%');
        }
        if ($request->filled('user_full_name')) {
            $storeLists->where(function ($query) use ($request) {
                $query->where('users.first_name', 'like', "%" . $request->user_full_name . "%")
                    ->orWhere('users.last_name', 'like', "%" . $request->user_full_name . "%");
            });
        }
        if ($request->filled('user_mobile')) {
            $storeLists->where('users.mobile', 'like', "%" . $request->user_mobile . "%");
        }
        $storeLists = $storeLists->orderBy('store.id', 'desc')
            ->paginate(15);

        foreach ($storeLists as $index => $row) {
            $month_interval = $row->month_inrterval * 30;
            $from_date = Jalalian::forge($row->from_date)->format('Ymd');
            $to_date = Jalalian::forge($row->to_date)->format('Ymd');
            if ($from_date - $to_date > $month_interval) {
                $storeLists[$index]->status_subscription = 1;
            } else
                $storeLists[$index]->status_subscription = 0;
        }

        foreach ($storeLists as $index => $row) {
            $storeSubscription = PlanSubscriptions::storeHasSubscription($row->user_id);
            if ($storeSubscription == true)
                $storeLists[$index]->status_subscription = 1;
            else
                $storeLists[$index]->status_subscription = 0;

            $products = ProductSeller::where('status', '!=', 'deleted')
                ->where('store_id', $row->id)
                ->get();
            $storeLists[$index]->products = $products;

            $photos = Store_photo::where('store_id', $row->id)->get();
            $storeLists[$index]->photos = $photos;

            $messages = Message::where(function ($subQuery) use ($row) {
                $subQuery->where('user_id', '=', $row->user_id)
                    ->orWhere('receiver_id', '=', $row->user_id);
            })
                ->orderBy('message.created_at', 'desc')
                ->get();
            $storeLists[$index]->messages = $messages;
        }
//        if ($request->filled('subscription') && $request->subscription == 'active-subscription'){
//            $storeLists->where($storeLists->status_subscription , 1);
//            $storeLists = $storeLists->orderBy('store.id', 'desc')
//                ->paginate(15);
//        }
//        if ($request->filled('subscription') && $request->subscription == 'inactive-subscription'){
//            $storeLists->where($storeLists->status_subscription , 0);
//            $storeLists = $storeLists->orderBy('store.id', 'desc')
//                ->paginate(15);
//        }
        return view('admin.store.pending_store', compact('storeLists', 'provinces', 'guilds'));
    }

    public function edit($slug)
    {
        $store = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->where('store.slug', $slug->slug)
            ->select('store.name as store_name', 'users.mobile', 'users.email', 'users.email', 'store.min_pay', 'address.address', 'store.about',
                'store.phone_number_visibility', 'store.mobile_visibility', 'store.visible',
                 'store.user_name', 'guild.id as guild_id', 'store.pay_type', 'store.activity_type', 'store.slogan')
            ->first();
        $guilds = Guild::all();
        return view('admin.store.edit', compact('store', 'guilds', 'slug'));

    }

    public function update(updateStoreRequest $request, $slug)
    {
        $store = Store::where('slug', $slug->slug)->first();
        $store->name = $request->name;
        $store->pay_type = $request->pay_type;
        $store->activity_type = $request->activity_type;
        $store->min_pay = $request->min_pay;
        $store->guild_id = $request->guild;
        $store->about = $request->about;
        $store->slogan = $request->slogan;
        if ($request->filled('phone_number')) {
            $store->phone_number = $request->phone_number;
        }
        if ($request->visible && $request->visible == 'on') {
            $store->visible = 1;
        }
        // if ($request->hasFile('thumbnail_photo')) {
        //     $logoName = uniqid() . '.' . $request->thumbnail_photo->getClientOriginalExtension();
        //     $request->thumbnail_photo->move(public_path('image/store_photos'), $logoName);
        //     $store->thumbnail_photo = $logoName;
        // }
        $store->save();
        Address::where('id', $store->address_id)->update([
            'address' => $request->address,
        ]);
        Swal::success('موفقیت آمیز.', 'فروشگاه با موفقیت ویرایش شد');
        return redirect()->route('listOfProductSeller', $slug->user_name);

    }

    public function plan($slug)
    {
        $store = Store::where('slug', $slug->slug)->first();
        $planInfos = Plan::join('seller_plan_subscription_details', 'seller_plan_subscription_details.plan_id', '=', 'seller_plans.id')
            ->join('users', 'users.id', '=', 'seller_plan_subscription_details.user_id')
            ->join('store', 'store.user_id', '=', 'users.id')
            ->where('store.user_name', $slug->user_name)
            ->select('seller_plans.plan_name', 'seller_plan_subscription_details.from_date', 'seller_plan_subscription_details.to_date',
                'seller_plan_subscription_details.pay_id', 'seller_plans.id', 'seller_plan_subscription_details.id as subscription_id')
            ->whereRaw("((store.store_type='product' OR store.store_type='service') AND seller_plans.type='store') OR(store.store_type=seller_plans.type)")
            ->get();
//        dd($planInfos);
        $plans = Plan::where('status', 'show')->get();
        return view('admin.store.plan_store', compact('plans', 'planInfos', 'store', 'slug'));
    }

    public function editPhoto($store)
    {
        $store = Store::where('user_name', $store)->first();
        $storePhotos = DB::table('store_photo')
            ->where('store_id', $store->id)
            ->get();
        return view('admin.store.edit_photo', compact('storePhotos', 'store'));
    }

    public function updatePhoto(uploadPhotoRequest $request, $store)
    {
        $store = Store::find($store);
        if ($request->hasFile('photo')) {
            foreach ($request->photo as $photo) {
                $imgName = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('/image/store_photos/'), $imgName);

                $store_photo = new Store_photo();
                $store_photo->store_id = $store->id;
                $store_photo->photo_name = $imgName;
                $store_photo->save();
            }
        } else {
            Swal::error('آپلود فایل', 'عکسی جهت آپلود انتخاب نشده است.');
            return redirect()->back();
        }
        Swal::success('آپلود فایل', 'فایل ها با موفقیت آپلود شدند.');
        return redirect()->back();
    }

    public function deletePhoto(Store_photo $store_photo)
    {
//        unlink(public_path('/image/store_photos/') . $store_photo->photo_name);
        $store_photo->delete();
        Swal::success('حذف موفقیت آمیز.', 'عکس مورد نظر با موفقیت حذف شد.');
        return redirect()->back();
    }

    public function setPlan(Request $request, $slug)
    {
        $request->validate([
            'plan' => 'required|numeric|exists:seller_plans,id'
        ]);
        $store = Store::where('user_name', $slug->user_name)->first();

        $planSub = PlanSubscription::where('user_id', $store->user_id)->latest()->first();
        $validDay = 0;
        if (is_array($planSub) && count($planSub) > 0) {
            $to_date = Carbon::createFromFormat('Y-m-d', $planSub->to_date);
            $validDay = $to_date->diffInDays(Carbon::now());
        }

        $plan = Plan::where('id', $request->plan)->first();
        $fromDate = Carbon::today()->format('Y-m-d');
        $toDate = Carbon::now()->addMonths($plan->month_inrterval)->format('Y-m-d');

        if ($validDay > 0) {
            $planSubscription = new PlanSubscription();
            $planSubscription->plan_id = $plan->id;
            $planSubscription->user_id = $store->user_id;
            $planSubscription->price = $plan->price;
            $planSubscription->from_date = $fromDate;
            $planSubscription->plan_type = $plan->type;
            $planSubscription->to_date = $to_date->addMonths($plan->month_inrterval);
            $planSubscription->save();
        } else {
            $planSubscription = new PlanSubscription();
            $planSubscription->plan_id = $plan->id;
            $planSubscription->user_id = $store->user_id;
            $planSubscription->price = $plan->price;
            $planSubscription->from_date = $fromDate;
            $planSubscription->plan_type = $plan->type;
            $planSubscription->to_date = $toDate;
            $planSubscription->save();
        }

        Swal::success(' موفقیت آمیز.', 'فعالسازی پلن برای این فروشگاه با موفقیت انجام شد.');
        return redirect()->back();
    }

    public function getCityByAjax($province)
    {
        $citys = DB::table('city')
            ->where('province_id', $province)
            ->where('deleted', 0)
            ->get();
        return response()->json($citys);
    }

    public function makeVisible($store, Request $request)
    {
        if ($request->id && $request->id == 1) {
            $store = Store::find($store);
            $store->visible = 1;
            $store->save();
        } elseif ($request->id && $request->id == 2) {
            $prodcutSeller = ProductSeller::find($store);
            $prodcutSeller->visible = 1;
            $prodcutSeller->save();
        }

        return response()->json(['status' => 200]);
    }

    public function makeInvisible($store, Request $request)
    {

        if ($request->id && $request->id == 1) {
            $store = Store::find($store);
            $store->visible = 0;
            $store->save();
        } elseif ($request->id && $request->id == 2) {
            $prodcutSeller = ProductSeller::find($store);
            $prodcutSeller->visible = 0;
            $prodcutSeller->save();
        }

        return response()->json(['status' => 200]);
    }

    public function makeApprovedStatus($store, Request $request)
    {
        $user = auth()->guard('web')->user();
        if ($request->id && $request->id == 1) {
            $stores = Store::find($store);
            $stores->status = 'approved';
            $stores->save();
            event(new ApproveStore($stores , $user));
        } elseif ($request->id && $request->id == 2) {
            $prodcutSeller = ProductSeller::find($store);
            $prodcutSeller->status = 'approved';
            $prodcutSeller->save();
        } elseif ($request->id && $request->id == 3) {
            $productSellerComment = ProductSellerComment::find($store);
            $productSellerComment->status = 'approved';
            $productSellerComment->save();
        } elseif ($request->id && $request->id == 4) {
            $billStatus = Bill::find($store);
            $billStatus->status = 'delivered';
            $billStatus->save();
        }

        return response()->json('status', 200);
    }

    public function makeRejectStatus($store, Request $request)
    {
        if ($request->id && $request->id == 1) {
            $store = Store::find($store);
            $store->status = 'rejected';
            $store->save();
        } elseif ($request->id && $request->id == 2) {
            $prodcutSeller = ProductSeller::find($store);
            $prodcutSeller->status = 'rejected';
            $prodcutSeller->save();
        } elseif ($request->id && $request->id == 3) {
            $productSellerComment = ProductSellerComment::find($store);
            $productSellerComment->status = 'rejected';
            $productSellerComment->save();
        } elseif ($request->id && $request->id == 4) {
            $billStatus = Bill::find($store);
            $billStatus->status = 'rejected';
            $billStatus->save();
        }

        return response()->json('status', 200);
    }

    public function makePendingStatus($store, Request $request)
    {
        if ($request->id && $request->id == 1) {
            $store = Store::find($store);
            $store->status = 'pending';
            $store->save();
        } elseif ($request->id && $request->id == 2) {
            $prodcutSeller = ProductSeller::find($store);
            $prodcutSeller->status = 'pending';
            $prodcutSeller->save();
        } elseif ($request->id && $request->id == 3) {
            $productSellerComment = ProductSellerComment::find($store);
            $productSellerComment->status = 'pending';
            $productSellerComment->save();
        } elseif ($request->id && $request->id == 4) {
            $billStatus = Bill::find($store);
            $billStatus->status = 'pending';
            $billStatus->save();
        }

        return response()->json('status', 200);
    }

    public function show_store($store)
    {
        Store::where('id', $store)->update(['visible' => 1]);
        return redirect()->back();
    }

    public function hide_store($store)
    {
        Store::where('id', $store)->update(['visible' => 0]);
        return redirect()->back();
    }

    public function approved_store($store)
    {
        $store = Store::find($store);
        $store->update(['status' => 'approved']);
        $user = $store->user;
        event(new ApproveStore($store , $user));

        return redirect()->back();
    }

    public function pending_store($store)
    {
        Store::where('id', $store)->update(['status' => 'pending']);
        return redirect()->back();
    }

    public function reject_store(Request $request ,$store)
    {   
        $store = Store::find($store);
        if($store->status == 'rejected'){
            return redirect()->back();
        }
        $store->update(['status' => 'rejected']);
        event(new RejectStore($store , $request->customMessage));
        return redirect()->back();
    }

    public function showCreateStorePage()
    {
        if(Store::where('user_id' , auth()->guard('web')->user()->id)->where('store_type' , 'product')->first()){
            return 'access denied';
        }
        $provinces = Province::where('deleted', 0)->get();
        $guilds = Guild::where('guild_type' , 'product')->get();
        $addresses = Address::where('status', 'active')
            ->where('user_id', auth()->guard('web')->user()->id)
            ->select('id', 'address')
            ->get();
        $hasSubscription = PlanSubscriptions::storeHasSubscription(auth()->guard('web')->user()->id);
        $hasAddress = count($addresses) > 0;

        return view('frontend.my-account.store.create', compact('guilds', 'addresses', 'provinces', 'hasSubscription', 'hasAddress'));
    }
    public function showCreateServiceStorePage()
    {
        if(Store::where('user_id' , auth()->guard('web')->user()->id)->where('store_type' , 'service')->first()){
            return 'access denied';
        }
        $provinces = Province::where('deleted', 0)->get();
        $guilds = Guild::where('guild_type' , 'service')->get();
        $addresses = Address::where('status', 'active')
            ->where('user_id', auth()->guard('web')->user()->id)
            ->select('id', 'address')
            ->get();
        $hasSubscription = PlanSubscriptions::storeHasSubscription(auth()->guard('web')->user()->id);
        $hasAddress = count($addresses) > 0;

        return view('frontend.my-account.store.service.create', compact('guilds', 'addresses', 'provinces', 'hasSubscription', 'hasAddress'));
    }
    public function showCreateMarketStorePage()
    {
        if (Store::where('user_id', auth()->guard('web')->user()->id)->where('store_type', 'market')->first()) {
            return 'access denied';
        }
        $provinces = Province::where('deleted', 0)->get();
        $guilds = Guild::all();
        $addresses = Address::where('status', 'active')
        ->where('user_id', auth()->guard('web')->user()->id)
            ->select('id', 'address')
            ->get();
        $hasSubscription = PlanSubscriptions::storeHasMarketSubscription(auth()->guard('web')->user()->id);
        $hasAddress = count($addresses) > 0;

        return view('frontend.my-account.store.market.create', compact('guilds', 'addresses', 'provinces', 'hasSubscription', 'hasAddress'));
    }
    public function store(StoreRequest $request)
    {
        $user = auth()->guard('web')->user();
        $existsStore = Store::where('user_id', $user->id)
            ->where('store_type' , $request->store_type)->count();
        if($existsStore >= 2){
            return 'maximum store reached';
        }
        if ($existsStore == 0) {
            if($request->store_type != 'market'){
            $guild = Guild::find($request->guild);
            if($guild->guild_type != $request->store_type){
                return "wrong guild";
            }
        }
            $store = new Store();
            $store->user_id = $user->id;
            $store->slogan = $request->slogan;
            $store->address_id = $request->address;
            $store->guild_id = $request->guild;
            $store->name = $request->store_name;
            $store->user_name = $request->username;
            $store->min_pay = $request->min_pay;
            $store->about = $request->about;
            $store->phone_number = $request->telephone_number;
            $store->pay_type = 'both';
            $store->activity_type = $request->activity_type;
            $store->store_type = $request->store_type;
            $store->gift_assigned_to_refferer = false;
            if ($request->filled('visible'))
                $store->visible = 1;
            if ($request->filled('show_telephone_number'))
                $store->phone_number_visibility = 'show'; else $store->phone_number_visibility = 'hide';
            // if ($request->hasFile('thumbnail_photo')) {
            //     $thumbnail_photo = $request->thumbnail_photo;
            //     $fileName = uniqid() . '.' . $thumbnail_photo->getClientOriginalExtension();
            //     $thumbnail_photo->move(public_path('/image/store_photos/'), $fileName);
            //     $store->thumbnail_photo = $fileName;
            // }
            $store->save();
//--------------------------------------------------------------------------------
            Swal::success('ثبت موفقیت آمیز فروشگاه.', 'ثبت فروشگاه برای شما با موفقیت انجام شد.');
            return redirect()->route('index.store.photo', $store->id);
        } else {
            Swal::error('خطا.', 'قبلا یک فروشگاه با این نام کاربری برای شما ثبت شده است.');
            return back();
        }

    }

    function make_url_validate($url)
    {
        if (stripos($url, "http://") === false && stripos($url, "https://") === false) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public function editStore()
    {
        $user = auth()->guard('web')->user();
        $storeValidatePlan = PlanSubscriptions::storeHasSubscription($user->id);

        $planSubs = PlanSubscription::where('user_id', $user->id)->where('plan_type', 'store')->latest()->first();
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

        $store = Store::where('user_id', auth()->guard('web')->user()->id)->where('store_type' , 'product')->first();
        $provinces = Province::where('deleted', 0)->get();
        $guilds = Guild::where('guild_type' , 'product')->get();
        $addresses = Address::where('status', 'active')
            ->where('user_id', auth()->guard('web')->user()->id)
            ->select('id', 'address')
            ->get();
        $positions = UpgradePosition::all();
        $discounts = Discount::where('admin_made' , false)->whereIn('discountable_type' , ['store' , 'store-sending'])->where('discountable_id' , $store->id)->paginate(20);
        return view('frontend.my-account.store.edit', compact('store', 'guilds', 'addresses', 'provinces', 'storeValidatePlan', 'intervalDays' , 'positions' , 'discounts'));
    }
    public function editServiceStore()
    {
        $user = auth()->guard('web')->user();
        $storeValidatePlan = PlanSubscriptions::storeHasSubscription($user->id);

        $planSubs = PlanSubscription::where('user_id', $user->id)->where('plan_type', 'store')->latest()->first();
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

        $store = Store::where('user_id', auth()->guard('web')->user()->id)->where('store_type' , 'service')->first();
        $provinces = Province::where('deleted', 0)->get();
        $guilds = Guild::where('guild_type' , 'service')->get();
        $addresses = Address::where('status', 'active')
            ->where('user_id', auth()->guard('web')->user()->id)
            ->select('id', 'address')
            ->get();
        $positions = UpgradePosition::all();
        $discounts = Discount::where('admin_made', false)->whereIn('discountable_type', ['store', 'store-sending'])->where('discountable_id', $store->id)->paginate(20);
        return view('frontend.my-account.store.service.edit', compact('store', 'guilds', 'addresses', 'provinces', 'storeValidatePlan', 'intervalDays' , 'positions' , 'discounts'));
    }
    public function editMarketStore()
    {
        $user = auth()->guard('web')->user();
        $storeValidatePlan = PlanSubscriptions::storeHasMarketSubscription($user->id);
        $planSubs = PlanSubscription::where('user_id', $user->id)->where('plan_type' , 'market')->latest()->first();
        $intervalDays = 0;
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

        $store = Store::where('user_id', auth()->guard('web')->user()->id)->where('store_type', 'market')->first();
        $provinces = Province::where('deleted', 0)->get();
        $guilds = Guild::all();
        $addresses = Address::where('status', 'active')
        ->where('user_id', auth()->guard('web')->user()->id)
            ->select('id', 'address')
            ->get();
        $positions = UpgradePosition::all();
        return view('frontend.my-account.store.market.edit', compact('store', 'guilds', 'addresses', 'provinces', 'storeValidatePlan', 'intervalDays', 'positions'));
    }
    public function updateStore(UpdateRequest $request)
    {
        $store = Store::where('user_id', auth()->guard('web')->user()->id)->where('store_type' , $request->store_type)->first();
        if(!$store){
            return 'Error';
        }
        $store->slogan = $request->slogan;
        $store->address_id = $request->address;
        $store->name = $request->store_name;
        $store->min_pay = $request->min_pay;
        $store->about = $request->about;
        $store->phone_number = $request->telephone_number;
        $store->status = 'pending';
        $store->pay_type = 'both';
        $store->activity_type = $request->activity_type;
        $store->store_type = $request->store_type;

        if ($request->filled('visible'))
            $store->visible = 1;
        if ($request->filled('show_telephone_number'))
            $store->phone_number_visibility = 'show'; else $store->phone_number_visibility = 'hide';
        $store->save();

        Swal::success('ویرایش موفقیت آمیز فروشگاه.', 'ویرایش فروشگاه شما با موفقیت انجام شد.');
        return redirect()->route('index.store.photo', $store->id);
    }

    public function checkUsernameByAjax(Request $request)
    {
        $storeUsername = Store::where('user_name', $request->username)->count();
        if ($storeUsername > 0)
            return response()->json([ "status" => 200], 200);
        else
            return response()->json(["stauts" => 400], 400);
    }

    public function showStore(Store $store, Request $request)
    {
        $request->validate([
            'orderBy' => 'nullable|string|in:newest,popular,lowest_price,highest_price',
            'min_price' => 'nullable|numeric',
            'max_price' => 'nullable|numeric',
            'visible' => 'nullable|string|in:on',
            'category' => new CategoryRule(),
        ]);
        $page = $request->viewMore;
        $offset = ($page - 1) * 8;
        $limit = 8;

        $store->rate = Rate::where('store_id', $store->id)->avg('rate');
        $store->address = $store->address->address;
        $store->mobile = $store->address()->first()->phone_number;
        $store->thumbnail_photo = $store->user->thumbnail_photo;
        $photos = [$store->photo];
        $categories = $store->guild->categories;
        $products = $store->products()->select('product_seller.id', 'name', 'price', 'category_id', 'status', 'visible', 'discount', 'hint')
            ->where('status', 'approved')
            ->where('visible', 1)
            ->where('product_seller.quantity', '>', 0);
        if ($request->filled('orderBy')) {
            if ($request->orderBy == 'newest')
                $products->orderBy('id', 'desc');
            if ($request->orderBy == 'popular')
                $products->orderBy('hint', 'desc');
            if ($request->orderBy == 'lowest_price')
                $products->orderBy('price', 'asc');
            if ($request->orderBy == 'highest_price')
                $products->orderBy('price', 'desc');
        }
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $products->where('price', '>=', $request->min_price)
                ->where('price', '<=', $request->max_price);
        }
        if ($request->filled('visible')) {
            $products->where('quantity', '>', 0);
        }
        if ($request->filled('category') && $request->category != 'all') {
            $products->where('category_id', $request->category);
        }
        $products = $products
            ->offset($offset)
            ->limit($limit)
            ->get();
        foreach ($products as $index => $prod) {
            $products[$index]->discountPrice = $prod->price - ($prod->price * ($prod->discount / 100));
            $products[$index]->photo = optional($prod->photos->first())->file_name;
        }
        if ($request->has('viewMore')) {
            return response()->json($products, 200);
        }
        $highestPrice = $store->products()->max('price');
        $lowestPrice = $store->products()->min('price');
        $address = $store->address()->first();
        if($store->status != 'approved'){
            return abort(404);
        }
        $discounts = Discount::where('admin_made' , false)->where('discountable_type' , 'store')->where('discountable_id' , $store->id)->get();
        $productDiscounts = Discount::where('admin_made' , false)->whereIn('discountable_type' ,['product' , 'service'])
            ->join('product_seller' , 'discountable_id' , '=' ,'product_seller.id')->where('store_id' , $store->id)
            ->select('discounts.*' , 'product_seller.id as product_seller_id')
            ->get();
        $discounts = $discounts->merge($productDiscounts);
        $discountables = $products->where('discount' , '>' , '0');
        return view('frontend.store.index', compact('store', 'highestPrice', 'lowestPrice', 'photos', 'categories', 'products', 'address' , 'discounts' , 'discountables'));
    }

    public function storeUserShabaCode(Request $request)
    {
        $store = Store::where('id', $request->store_id)->first();
        $store->shaba_code = $request->shaba;
        $store->save();

        return response()->json([ "status" => 200], 200);
    }

    public function storesListPage(Request $request)
    {
        $guilds = Guild::all();
        $data['guilds'] = $guilds;
        $lastStoresCreate = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('users' , 'store.user_id' , '=' , 'users.id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->where('province.id', 8)
            ->where('store.status', 'approved')
            ->where('store.visible', 1)
            ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.slug', 'store.user_name')
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store.id = store_rate.store_id
            ) as rate'))
            ->paginate(8);
        $data['stores'] = $lastStoresCreate;
        return view('frontend.list.stores-index')->with($data);
    }

    public function listStores(Request $request)
    {
        $guilds = Guild::all();
        if ($request->cookie('province')) {
            $province = Cookie::get('province');
        } else {
            $province = 4;
        }
        $stores = Store::join('address', 'address.id', '=', 'store.address_id')
            ->join('users', 'users.id', '=', 'store.user_id')
            ->join('guild', 'guild.id', '=', 'store.guild_id')
            ->join('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->where(function ($activityTypeSubQuery) use ($province) {
                $activityTypeSubQuery->where('store.activity_type', 'country')
                    ->orWhere(function ($subWhere) use ($province) {
                        $subWhere->where('store.activity_type', 'province')
                            ->where('province.id', $province);
                    });
            })
            ->whereRaw(RawQueries::hasProductsForStore())
            ->whereRaw(RawQueries::hasSubscriptionForStore())
            ->where('store.status', 'approved')
            ->where('store.visible', 1)
            ->select('store.id', 'store.name', 'store.slogan', 'users.thumbnail_photo', 'store.slug', 'store.user_name', 'store.activity_type', 'store.created_at')
            ->addSelect(DB::raw('(
                select avg(store_rate.rate)
                from store_rate
                where store.id = store_rate.store_id
            ) as rate'));
        if ($request->has('store') && $request->store == 'top')
            $stores->orderBy('rate', 'desc');
        else
            $stores->latest();

        if ($request->has('guild')) {
            $stores->where('store.guild_id', $request->guild);
        }
        $stores = $stores->paginate(15)
            ->appends([
                'store' => $request->store,
                'guild' => $request->guild
            ]);
        // $stores->each(function ($store) {
        //     $store->photo = optional($store->photos->first())->photo_name;
        // });
        return view('frontend.store.list', compact('guilds', 'stores'));
    }

    public function setRate(Request $request)
    {
        $user = auth()->guard('web')->user();
        $rate = Rate::where('user_id', $user->id)
            ->where('store_id', $request->store_id);
        if ($rate->exists()) {
            $rate = $rate->first();
            $rate->update(['rate' => $request->rate]);
        } else {
            Rate::create([
                'user_id' => $user->id,
                'store_id' => $request->store_id,
                'rate' => $request->rate,
            ]);
        }

        return response()->json([ "status" => 200], 200);
    }

    public function exportAllMobileExcel(Request $request)
    {
        $storesCount = Store::count();
        $this->validate($request , [
            'from_index' => 'required|numeric|min:0|max:' . ($storesCount - 1)
        ] , [
            'from_index.required' => 'وارد کردن ردیف ابتدایی الزامی است.',
            'from_index.numeric' => 'ردیف ابتدایی باید به صورت عددی وارد شود.',
            'from_index.min' => 'ردیف ابتدایی باید حداقل صفر باشد.',
            'from_index.max' => 'ردیف ابتدایی حداکثر می تواند برابر با تعداد کل کاربران باشد.',
        ]);
        return Excel::download(new StoresMobileExport($request->from_index), 'stores.xlsx');

    }

    public function getStoresViaAjax(Request $request)
    {
        $this->validate($request, [
            'q' => 'required|string|max:255'
        ]);
        $storesList = Store::orderBy('name', 'asc')
            ->where('name', 'like', "%" . $request->q . "%")
            ->get();

        return response()->json($storesList);
    }

    public function adminStoreCreate(Request $request, User $user)
    {
        $guilds = Guild::all();
        $addresses = $user->addresses()->where('status', 'active')->get();
        $provinces = Province::where('deleted', 0)->get();
        return view('admin.store.create', compact('user', 'guilds', 'addresses', 'provinces'));
    }


    public function checkUserNameDuplication(Request $request)
    {
        $username = $request->username;
        if (strlen($username) == 0) {
            return response()->json([
                'status' => 400
            ]);
        }
        $exists = Store::where('user_name', $username)->exists();
        if ($exists) {
            return response()->json([
                'status' => 400
            ]);
        }
        return response()->json([
            'status' => 200
        ]);
    }

    public function saveStore(Request $request)
    {
        $this->validate($request, [
            'store_name' => 'required|string|max:200',
            'store_slogan' => 'required|string',
            'guild' => 'required|numeric|exists:guild,id',
            'address' => 'required|numeric|exists:address,id',
            'username' => 'required|string|unique:store,user_name',
            'min_pay' => 'required|numeric|min:0',
            'store_type' => 'required|in:product,service',
            'status' => 'required|string|in:pending,approved,rejected',
            'visible' => 'required|string|in:yes,no',
            'about' => 'required|string',
            'tel' => 'required|string|max:200',
            'pay_type' => 'required|string|in:online,postal,both',
            'activity_type' => 'required|string|in:country,province',
            'shaba_code' => 'nullable|string|max:26',
            'photo' => 'array|max:1',
            'photo.*' => 'nullable|file'
        ], [
            'store_name.required' => 'نام فروشگاه الزامی است.',
            'store_name.string' => 'نام فروشگاه نامعتبر است.',
            'store_name.max' => 'نام فروشگاه طولانی تر از حد مجاز است.',
            'store_slogan.required' => 'شعار فروشگاه الزامی است.',
            'store_slogan.string' => 'شعار فروشگاه نامعتبر است.',
            'guild.required' => 'صنف الزامی است.',
            'guild.numeric' => 'صنف نامعتبر است.',
            'guild.exists' => 'صنف نامعتبر است.',
            'address.required' => 'آدرس الزامی است.',
            'address.numeric' => 'آدرس نامعتبر است.',
            'address.exists' => 'آدرس نامعتبر است.',
            'username.required' => 'نام کاربری الزامی است.',
            'username.string' => 'نام کاربری نامعتبر است.',
            'username.unique' => 'نام کاربری از قبل توسط کاربران دیگر ثبت شده است.',
            'min_pay.required' => 'حداقل مبلغ خرید از فروشگاه الزامی است.',
            'min_pay.numeric' => 'حداقل مبلغ خرید از فروشگاه نامعتبر است.',
            'min_pay.min' => 'کمترین میزان خرید از فروشگاه باید صفر باشد.',
            'store_type.required' => 'نوع فروشگاه الزامی است',
            'store_type.in' => 'نوع فروشگاه را به درستی وارد نمایید',
            'status.required' => 'انتخاب وضعیت فروشگاه الزامی است.',
            'status.string' => 'وضعیت فروشگاه نامعتبر است.',
            'status.in' => 'وضعیت فروشگاه تنها می تواند یکی از مقادیر در انتظار تایید، تایید شده یا رد شده باشد.',
            'visible.required' => 'فیلد نمایش فروشگاه الزامی است.',
            'visible.string' => 'فیلد نمایش فروشگاه نامعتبر است.',
            'visible.in' => 'فیلد نمایش فروشگاه تنها می تواند یکی از مقادیر بله یا خیر باشد.',
            'about.required' => 'فیلد درباره فروشگاه الزامی است.',
            'about.string' => 'فیلد درباره فروشگاه نامعتبر است.',
            'tel.required' => 'تلفن تماس الزامی است.',
            'tel.string' => 'تلفن تماس نامعتبر است.',
            'tel.max' => 'تلفن تماس طولانی تر از حد مجاز است.',
            'pay_type.required' => 'روش پرداخت الزامی است.',
            'pay_type.string' => 'روش پرداخت نامعتبر است.',
            'pay_type.in' => 'فیلد روش پرداخت تنها می تواند یکی از مقادیر آنلاین، نقدی یا هر دو را داشته باشد.',
            'activity_type.required' => 'فیلد محدوده فعالیت الزامی است.',
            'activity_type.string' => 'فیلد محدوده فعالیت نامعتبر است.',
            'activity_type.in' => 'فیلد محدوده فعالیت تنها می تواند یکی از مقادیر در استان یا در کشور را داشته باشد.',
            'shaba_code.string' => 'شماره شبای فروشنده نامعتبر است.',
            'shaba_code.max' => 'شماره شبای فروشنده طولانی تر از حد مجاز است.',
        ]);

        $store = new Store();
        $store->user_id = $request->user_id;
        $store->slogan = $request->store_slogan;
        $store->address_id = $request->address;
        $store->guild_id = $request->guild;
        $store->slug = SlugService::createSlug(Store::class, 'slug', $request->store_name);
        $store->name = $request->store_name;
        $store->user_name = $request->username;
        $store->min_pay = $request->min_pay;
        $store->store_type = $request->store_type;
        $store->status = $request->status;
        if ($request->visible == 'yes') {
            $store->visible = 1;
        } else {
            $store->visible = 0;
        }
        $store->about = $request->about;
        $store->phone_number = $request->tel;
        // if ($request->hasFile('thumbnail_photo')) {
        //     $imgName = uniqid() . '.' . $request->thumbnail_photo->getClientOriginalExtension();
        //     $request->thumbnail_photo->move(public_path('image/store_photos'), $imgName);
        //     $store->thumbnail_photo = $imgName;
        // }
        $store->phone_number_visibility = $request->has('phone_number_visibility') ? 'show' : 'hide';
        $store->mobile_visibility = $request->has('mobile_visibility') ? 'show' : 'hide';
        $store->pay_type = $request->pay_type;
        $store->activity_type = $request->activity_type;
        $store->shaba_code = $request->shaba_code;

        $store->save();
        if ($request->hasFile('photo')) {
            foreach ($request->photo as $photo) {
                if($photo){
                    $imgName = uniqid() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(public_path('image/store_photos') , $imgName);
                    $pPhoto = new Store_photo();
                    $pPhoto->store_id = $store->id;
                    $pPhoto->photo_name = $imgName;
                    $pPhoto->save();
                }

            }
        }
        Swal::success('ثبت فروشگاه', 'فروشگاه با موفقیت ثبت شد.');
        return redirect()->route('showListOfUsers');
    }

    public function editStoreInAdmin(Request $request, Store $store)
    {
        $user = $store->user;
        $provinces = Province::all();
        $guilds = Guild::all();
        $addresses = $user->addresses;

        return view('admin.store.edit-store', compact('user', 'store', 'provinces', 'guilds', 'addresses'));
    }

    public function updateStoreAdmin(Request $request, Store $store)
    {
        $validator = Validator::make($request->all(), [
            'store_name' => 'required|string|max:200',
            'store_slogan' => 'required|string',
            'guild' => 'required|numeric|exists:guild,id',
            'address' => 'required|numeric|exists:address,id',
            'min_pay' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,approved,rejected',
            'visible' => 'required|string|in:yes,no',
            'about' => 'required|string',
            'tel' => 'required|string|max:200',
            'pay_type' => 'required|string|in:online,postal,both',
            'activity_type' => 'required|string|in:country,province',
            'shaba_code' => 'nullable|string|max:26'
        ], [
            'store_name.required' => 'نام فروشگاه الزامی است.',
            'store_name.string' => 'نام فروشگاه نامعتبر است.',
            'store_name.max' => 'نام فروشگاه طولانی تر از حد مجاز است.',
            'store_slogan.required' => 'شعار فروشگاه الزامی است.',
            'store_slogan.string' => 'شعار فروشگاه نامعتبر است.',
            'guild.required' => 'صنف الزامی است.',
            'guild.numeric' => 'صنف نامعتبر است.',
            'guild.exists' => 'صنف نامعتبر است.',
            'address.required' => 'آدرس الزامی است.',
            'address.numeric' => 'آدرس نامعتبر است.',
            'address.exists' => 'آدرس نامعتبر است.',
            'min_pay.required' => 'حداقل مبلغ خرید از فروشگاه الزامی است.',
            'min_pay.numeric' => 'حداقل مبلغ خرید از فروشگاه نامعتبر است.',
            'min_pay.min' => 'کمترین میزان خرید از فروشگاه باید صفر باشد.',
            'status.required' => 'انتخاب وضعیت فروشگاه الزامی است.',
            'status.string' => 'وضعیت فروشگاه نامعتبر است.',
            'status.in' => 'وضعیت فروشگاه تنها می تواند یکی از مقادیر در انتظار تایید، تایید شده یا رد شده باشد.',
            'visible.required' => 'فیلد نمایش فروشگاه الزامی است.',
            'visible.string' => 'فیلد نمایش فروشگاه نامعتبر است.',
            'visible.in' => 'فیلد نمایش فروشگاه تنها می تواند یکی از مقادیر بله یا خیر باشد.',
            'about.required' => 'فیلد درباره فروشگاه الزامی است.',
            'about.string' => 'فیلد درباره فروشگاه نامعتبر است.',
            'tel.required' => 'تلفن تماس الزامی است.',
            'tel.string' => 'تلفن تماس نامعتبر است.',
            'tel.max' => 'تلفن تماس طولانی تر از حد مجاز است.',
            'pay_type.required' => 'روش پرداخت الزامی است.',
            'pay_type.string' => 'روش پرداخت نامعتبر است.',
            'pay_type.in' => 'فیلد روش پرداخت تنها می تواند یکی از مقادیر آنلاین، نقدی یا هر دو را داشته باشد.',
            'activity_type.required' => 'فیلد محدوده فعالیت الزامی است.',
            'activity_type.string' => 'فیلد محدوده فعالیت نامعتبر است.',
            'activity_type.in' => 'فیلد محدوده فعالیت تنها می تواند یکی از مقادیر در استان یا در کشور را داشته باشد.',
            'shaba_code.string' => 'شماره شبای فروشنده نامعتبر است.',
            'shaba_code.max' => 'شماره شبای فروشنده طولانی تر از حد مجاز است.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all())
                ->withInput();
        }

        $store->user_id = $request->user_id;
        $store->slogan = $request->store_slogan;
        $store->address_id = $request->address;
        $store->guild_id = $request->guild;
        $store->name = $request->store_name;
        $store->min_pay = $request->min_pay;
        $store->status = $request->status;
        if ($request->visible == 'yes') {
            $store->visible = 1;
        } else {
            $store->visible = 0;
        }
        $store->about = $request->about;
        $store->phone_number = $request->tel;
        // if ($request->hasFile('thumbnail_photo')) {
        //     $imgName = uniqid() . '.' . $request->thumbnail_photo->getClientOriginalExtension();
        //     $request->thumbnail_photo->move(public_path('image/store_photos'), $imgName);
        //     $store->thumbnail_photo = $imgName;
        // }
        $store->phone_number_visibility = $request->has('phone_number_visibility') ? 'show' : 'hide';
        $store->mobile_visibility = $request->has('mobile_visibility') ? 'show' : 'hide';
        $store->pay_type = $request->pay_type;
        $store->activity_type = $request->activity_type;
        $store->shaba_code = $request->shaba_code;

        $store->save();
        Swal::success('ویرایش فروشگاه', 'فروشگاه با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function getStoresForAdsPageViaAjax(Request $request){
        $this->validate($request , [
            'q' => 'required|string|min:3',
        ]);
        $stores = Store::where('status' , '!=' , 'deleted')
           ->where('name' , 'like' , "%". $request->q ."%")
            ->get();
        return response()->json($stores, 200);
    }
    public function marketProducts(Request $request){
        $userStore = Store::where('user_id', auth()->guard('web')->user()->id)
            ->where('store_type', 'market')
            ->first();
        $products = $userStore->products;
        foreach ($products as $index => $product) {
            $products[$index]->photo = Product_seller_photo::where('seller_product_id', $product->id)->first();
        }
        $positions = UpgradePosition::all();
        $is_service = false;
        return view('frontend.my-account.store.market.items' , compact('userStore' , 'products' , 'positions' , 'is_service'));
    }
    public function addProductToMarket(Request $request , $product_id){
        $market = Store::where('user_id', auth()->guard('web')->user()->id)
            ->where('store_type', 'market')
            ->first();
        $product = ProductSeller::find($product_id);
        if(!$product){
            Swal::error('خطا' , 'محصول یا خدمت مورد نظر یافت نشد');
            return redirect()->back();
        }
        if(!$market){
            Swal::error('خطا', 'لطفا ابتدا فروشگاه بازاریابی خود را ایجاد کنید');
            return redirect()->back();
        }
        if($product->store->user_id == auth()->guard('web')->user()->id){
            Swal::error('خطا', 'شما نمیتوانید محصولات و خدمات خودتان را به فروشگاه بازاریابی اضافه کنید');
            return redirect()->back();
        }
        // if($product->store->category->guild->id != $market->category->guild->id){
        //     Swal::error('خطا', 'صنف فروشگاه بازاریابی شما با صنف محصول همخوانی ندارد');
        //     return redirect()->back();
        // }
        if(!$market->products()->where('product_seller.id' , $product->id)->exists())
        $market->products()->attach($product);
        if($product->store->store_type == "product")
        Swal::success('موفق' , 'محصول مورد نظر به فروشگاه بازاریابی اضافه شد');
        if ($product->store->store_type == "service")
        Swal::success('موفق', 'خدمت مورد نظر به فروشگاه بازاریابی اضافه شد');
        return redirect()->to(route('market.products'));
    }
    public function deleteProductFromMarket(Request $request,$product_id){
        $market = Store::where('user_id' , auth()->guard('web')->user()->id)->where('store_type' , 'market')->first();
        MarketProduct::where('market_id' , $market->id)->where('product_id' , $product_id)->delete();
        Swal::success('موفق' , 'با موفقیت حذف شد');
        return redirect()->back();
    }
    public function commissions(){
        $categories = Category::all();
        $commissions = MarketCommission::paginate(10);
        return view('admin.marketing.commissions' , compact('categories' , 'commissions'));
    }
    public function addCommission(Request $request){
        $request->validate([
            'category' => 'required|exists:category,id|unique:market_commissions,category_id',
            'amount' => 'required|integer|min:0|max:100'
        ]);
        $commission = new MarketCommission();
        $commission->category_id = $request->category;
        $commission->amount = $request->amount;
        $commission->save();
        Swal::success('موفق' , 'پورسانت جدید با موفقیت ایجاد شد');
        return redirect()->back();
    }
    public function deleteCommission(Request $request , $commission_id){
        MarketCommission::where('id' , $commission_id)->delete();
        Swal::success('موفق', 'پورسانت با موفقیت حذف شد');
        return redirect()->back();
    }
    public function updateCommission(Request $request , $commission_id){
        $request->validate([
            'amount' => 'required|integer|min:0|max:100'
        ]);
        $commission = MarketCommission::find($commission_id);
        if(!$commission){
            return 'not found';
        }
        $commission->amount = $request->amount;
        $commission->save();
        Swal::success('موفق', 'پورسانت با موفقیت ویرایش شد');
        return redirect()->back();
    }
    public function getMyMarketers(){
        $userProducts = ProductSeller::join('store' , 'product_seller.store_id' , '=' , 'store.id')
            ->where('store_type' , '!=' , 'market')
            ->where('store.user_id' , auth()->guard('web')->user()->id)
            ->select('product_seller.id')
            ->pluck('id');
        $markets = MarketProduct::with('market' , 'market.user' , 'product')->whereIn('product_id' , $userProducts)->paginate(20);
        
        return view('frontend.my-account.marketers.index' , compact('markets'));
        
    }
    public function marketBillItems(Request $request){
        $user = User::find(auth()->guard('web')->user()->id);
        $billItems = BillItem::where('market_id' , $user->market->id)->paginate(10);
        return view('frontend.my-account.store.market.bill-items' , compact('billItems'));
    }
}
