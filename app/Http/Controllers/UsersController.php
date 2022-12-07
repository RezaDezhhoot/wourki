<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Exports\UsersMobileExport;
use App\Http\Requests\web\registerUserInAdminRequest;
use App\Libraries\Swal;
use App\Marketer;
use App\Message;
use App\Products;
use App\Setting;
use App\Store;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;

class UsersController extends Controller
{
    public function showList(Request $request)
    {
        $users = User::select('users.*', 'store.id as store_exists' , 'province.name as province_name' , 'city.name as city_name')
            ->addSelect(DB::raw('(
                select count(*)
                from reagent_code
                where reagent_code.user_id = users.id and
                reagent_code.type != "create_store"
            ) as reagent_user'))
            ->addSelect(DB::raw('(
                select count(*) 
                from marketer
                where marketer.user_id = users.id
            ) as isMarketer'))
            ->addSelect(DB::raw('(
                select store.user_name
                from store 
                where store.user_id = users.id
                limit 1
            ) as store_user_name'))
            ->addSelect(DB::raw('(
                select sum(wallet.cost)
                from wallet 
                where wallet.user_id = users.id
            ) as total_credit'))
            ->where('users.banned', 0)
            ->leftJoin('store', 'store.user_id', '=', 'users.id')
            ->leftJoin('address' , 'address.id' , '=' , 'store.address_id')
            ->leftJoin('city' , 'city.id' , '=' , 'address.city_id')
            ->leftJoin('province' , 'province.id' , '=' , 'city.province_id')
            ->leftJoin('reagent_code', 'reagent_code.user_id', '=', 'users.id')
            ->leftJoin('users as referrer_user', 'referrer_user.reagent_code', '=', 'reagent_code.reagent_code')
            ->addSelect('referrer_user.first_name as referrer_first_name')
            ->addSelect('referrer_user.last_name as referrer_last_name')
            ->groupBy('users.id',
            //  'users.first_name', 'users.last_name', 'users.password', 'users.mobile', 'users.mobile_confirmed', 'users.verify_mobile_token', 'users.shaba_code', 'users.gcm_code', 'users.reset_password_token', 'users.email', 'users.remember_token', 'users.verify_forget_password_token', 'users.banned', 'users.reagent_code', 'users.become_marketer',
            //     'users.created_at', 'users.updated_at' , 'province.name' , 'city.name' , 'store.id', 'referrer_user.first_name', 'referrer_user.last_name'
            //     , 'users.last_login_datetime', 'users.referrer_user_id',
            //     'users.created_at', 'users.updated_at', 'referrer_user.first_name', 'referrer_user.last_name'
            //     , 'store.id' ,
            //     'province.name' , 'city.name',
            //     'users.last_login_datetime', 'users.referrer_user_id'
            );
        if($request->filled('user_id')){
            $users->where('users.id' , '=' , $request->user_id);
        }
        if ($request->filled('name')) {
            $users->whereRaw('( MATCH(users.first_name , users.last_name) AGAINST("' . $request->name . '" IN NATURAL LANGUAGE MODE) )')
                ->addSelect(DB::raw('(
                      MATCH(users.first_name , users.last_name) AGAINST("' . $request->name . '" IN NATURAL LANGUAGE MODE)
                 ) as relevance'))
                ->orderBy('relevance' , 'desc');
        }else{
            $users->orderBy('created_at' , 'desc');
        }

        if ($request->has('mobile') && !empty($request->mobile)) {
            $users->where('users.mobile', 'like', "%" . $request->mobile . "%");
        }
        if ($request->has('email') && !empty($request->email)) {
            $users->where('users.email', '=', $request->email);
        }
        if ($request->has('become_marketer')) {
            $users->where('users.become_marketer', 1)
                ->whereNotIn('users.id', Marketer::select('user_id')->get()->toArray());
        }
        if ($request->filled('start_date_ts') && $request->filled('end_date_ts')) {
            $startDate = Carbon::createFromTimestamp($request->start_date_ts)->toDateString();
            $endDate = Carbon::createFromTimestamp($request->end_date_ts)->toDateString();
            $users->where(function ($dateQuery) use ($startDate, $endDate) {
                $dateQuery->whereDate('users.created_at', '>=', $startDate)
                    ->whereDate('users.created_at', '<=', $endDate);
            });
        }
        $list = $users->paginate(15)
            ->appends([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'become_marketer' => $request->become_marketer,
                'start_date_ts' => $request->start_date_ts,
                'end_date_ts' => $request->end_date_ts,
            ]);
        foreach($list as $index => $row){
            $newMessages = Message::where('user_id' , $row->id)->where('view' , 0)->count();
            $list[$index]->unread_msg = $newMessages;
            if($row->store_exists){
                $store = Store::find($row->store_exists);
                if($store){
                    $list[$index]->store_name = $store->name;
                    $list[$index]->submitted_date = Jalalian::forge($store->creaated_at)->format('datetime');
                }else{
                    $list[$index]->store_name = null;
                    $list[$index]->submitted_date = null;
                }
            }
        }
        $data['users'] = $list;
        $data['users_num'] = User::count();
        $data['default_excel_export_rows_limit'] = Setting::first()->excel_export_rows_num;
        return view('admin.users.list-of-users')->with($data);
    }

    public function showBannedList(Request $request)
    {
        $users = User::where('banned', '1');
        if ($request->has('name')) {
            $users = $users->whereRaw('( MATCH(users.first_name , users.last_name) AGAINST("' . $request->name . '" IN NATURAL LANGUAGE MODE) )');
        }
        if ($request->has('mobile')) {
            $users = $users->where('mobile', 'like', "%" . $request->mobile . "%");
        }
        if ($request->has('email')) {
            $users = $users->where('email', '=', $request->email);
        }
        $users = $users->select('users.*')
            ->addSelect(DB::raw('(
                select count(*)
                from reagent_code
                where reagent_code.user_id = users.id and
                reagent_code.type != "create_store"
            ) as reagent_user'));
        $list = $users->paginate(15);
        $data['users'] = $list;
        $data['users_num'] = User::count();
        $data['default_excel_export_rows_limit'] = Setting::first()->excel_export_rows_num;
        return view('admin.users.list-of-users')->with($data);
    }

    public function delete(User $user)
    {
        try {
            $user->delete();
        } catch (\Exception $e) {
            Swal::error('ناموفق!', 'کاربر دارای رکوردهای وابسته هست.');
        }
        return back();
    }

    public function bennUser(User $userId)
    {
        $userId->banned = 1;
        $userId->save();

        return redirect()->back();
    }

    public function activeUser(User $userId)
    {
        $userId->banned = 0;
        $userId->save();

        return redirect()->back();
    }

    public function editUser(User $userId)
    {
        $data['users'] = $userId;
        return view('admin.users.edit')->with($data);
    }

    public function updateUser(User $userId, Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
        ], [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'first_name.string' => 'نام نامعتبر است.',
            'first_name.max' => 'نام طولانی تر از حد مجاز است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
            'last_name.max' => 'نام خانوادگی طولانی تر از حد مجاز است.',
        ]);

        $userId->first_name = $request->first_name;
        $userId->last_name = $request->last_name;
        $userId->shaba_code = $request->shaba_code;
        $userId->mobile_confirmed = 1;
        $userId->save();

        Swal::success('موفقیت آمیز', 'کاربر مورد نظر با موفقیت به روزرسانی شد.');
        return redirect()->route('showListOfUsers');
    }

    public function createUserPage()
    {
        return view('admin.users.create');
    }

    public function register(registerUserInAdminRequest $request)
    {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile = $request->mobile;
        $user->shaba_code = $request->shaba_code;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->mobile_confirmed = 1;
        $user->reagent_code = $request->mobile;
        $user->save();

        Swal::success('موفقیت آمیز', 'کاربر مورد نظر با ثبت شد.');
        return redirect()->route('showListOfUsers');
    }

    public function showProfile()
    {
        $user = auth()->guard('web')->user();
        $data['user'] = $user;

        $bill = new Bill();
        $billQuery = $bill->dbSelect(Bill::FIELDS)
            ->where('user_id', '=', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $product = new Products();
        $productQuery = $product->dbSelect(Products::FIELDS)
            ->whereRaw('(
                product.id in (
                    select fav_product.product_id
                    from fav_product
                    where fav_product.user_id = ' . $user->id . '
                )
            ) ')->get();
        foreach ($productQuery as $index => $item) {
            if ($item->first_photo) {
                $productQuery[$index]->first_photo = \url()->to('/image/product_photos') . '/' . $item->first_photo;
            } else {
                $productQuery[$index]->first_photo = \url()->to('/image/product_photos/default-product.png');
            }
        }

        $data['bills'] = $billQuery;
        return view('frontend.user.index')->with($data);
    }

    public function updateInUserPanel(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'first_name' => 'nullable|string|max:200',
            'last_name' => 'nullable|string|max:200',
            'email' => 'nullable|email|unique:users,email',
        ], [
            'first_name.string' => 'نام نامعتبر است.',
            'first_name.max' => 'نام طولانی تر از حد مجاز است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
            'last_name.max' => 'نام خانوادگی طولانی تر از حد مجاز است.',
            'email.email' => 'ایمیل معتبر نیست.',
            'email.unique' => 'ایمیل وارد شده از قبل ثبت شده است.',
        ]);;

        $user = auth()->guard('web')->user();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->save();
        if ($user) {
            Swal::success('ویرایش موفقیت آمیز', 'ویرایش اطلاعات کاربری با موفقیت انجام شد.');
            return redirect()->back();
        }
    }

    public function profile()
    {
        $user = auth()->guard('web')->user();
        return view('frontend.my-account.user-info', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        // return $request->profile_photo;
        $request->validate([
            'first_name' => 'required|string',
            'returnPayType' => 'required|boolean',
            'shaba_code' => 'nullable|numeric|digits:24',
            'card' => 'nullable|numeric|digits:16',
            'last_name' => 'required|string',
            'profile_photo' => 'nullable|file|max:512',
            'about' => 'nullable|string'
        ], [
            'first_name.required' => 'نام الزامی است.',
            'first_name.string' => 'نام نامعتبر است.',
            'last_name.required' => 'نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
            'profile_photo.file' => 'لطفا تصویر پروفایل را به درستی انتخاب نمایید',
            'profile_photo.max'    => 'حجم عکس بندانگشتی حداکثر 512 کیلیبایت است.',
        ], [
            'returnPayType' => 'نوع بازپرداخت وجه : ',
            'shaba_code' => 'شبا',
            'card' => 'شماره کارت',
        ]);
        if ($request->hasFile('profile_photo')) {
            $imgName = uniqid() . '.' . $request->profile_photo->getClientOriginalExtension();
            $request->profile_photo->move(public_path('image/store_photos'), $imgName);
        }
        auth()->guard('web')->user()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'returnPayType' => $request->returnPayType,
            'shaba_code' => $request->shaba_code,
            'card' => $request->card,
            'about' => $request->about,
            'thumbnail_photo' => $request->hasFile('profile_photo') ? $imgName : auth()->guard('web')->user()->thumbnail_photo

        ]);
        Swal::success('ویرایش موفقیت آمیز.', 'اطلاعات کاربری با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ], [
            'old_password.required' => 'رمز عبور قبلی الزامی است.',
            'password.required' => 'رمز عبور جدید الزامی است.',
            'password.confirmed' => 'رمز عبور مطابقت ندارد.',
            'password.min' => 'رمز عبور  باید حداقل 6 کاراکتر باشد.',
        ]);

        $user = auth()->guard('web')->user();
        if (\Hash::check($request->old_password, $user->password)) {
            $user->update(['password' => bcrypt($request->password)]);
            Swal::success('تغییر پسورد موفقیت آمیز.', 'رمز عبور با موفقیت تغییر یافت.');
            return redirect()->back();
        } else {
            Swal::error('خطا!', 'رمز عبور قبلی اشتباه است.');
            return redirect()->back();
        }
    }


    public function exportAllMobileExcel(Request $request)
    {
        $usersCount = User::count();
        $this->validate($request , [
            'from_index' => 'required|numeric|min:0|max:' . ($usersCount - 1)
        ] , [
            'from_index.required' => 'وارد کردن ردیف ابتدایی الزامی است.',
            'from_index.numeric' => 'ردیف ابتدایی باید به صورت عددی وارد شود.',
            'from_index.min' => 'ردیف ابتدایی باید حداقل صفر باشد.',
            'from_index.max' => 'ردیف ابتدایی حداکثر می تواند برابر با تعداد کل کاربران باشد.',
        ]);
        return Excel::download(new UsersMobileExport($request->from_index), 'users.xlsx');
    }

    public function getViaAjax(Request $request)
    {
        $this->validate($request, [
            'q' => 'required|string|max:255'
        ]);
        $selectUsers = User::where('banned', 0)->orderBy('first_name', 'asc')
            ->where(function ($query) use ($request) {
                $query->where('first_name', 'like', "%" . $request->q . "%")
                    ->orWhere('last_name', 'like', "%" . $request->q . "%");
            })
            ->get();
        return response()->json($selectUsers);
    }

    public function searchInUsersByMobileAndName(Request $request){
        $this->validate($request, [
            'q' => 'required|string|max:255'
        ]);
        $selectUsers = User::where('banned', 0)->orderBy('first_name', 'asc')
            ->join('store' , 'store.user_id' , '=' , 'users.id')
            ->where(function ($query) use ($request) {
                $query
                    ->whereRaw('(MATCH(users.first_name , users.last_name) AGAINST("'. $request->q .'"))')
                    ->orWhere('mobile' , 'like' , "%". $request->q ."%")
                    ->orWhere('store.name' , 'like' , "%". $request->q ."%");
            })
            ->select('users.*' , 'store.name as store_name')
            ->addSelect(DB::raw('(
                MATCH(users.first_name , users.last_name) AGAINST("'. $request->q .'")
            ) as ordering_factor'))
            ->orderBy('ordering_factor' , 'desc')
            ->get();
        return response()->json($selectUsers);
    }

    public function getUserInfoById(Request $request , User $user){
        return $user;
    }

    public function quickUpdate(Request $request  , User $user){
        $validator = Validator::make($request->all() , [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'nullable|string',
            'shaba_code' => 'nullable|string|max:30',
            'email' => 'nullable|string|email'
        ] , [
            'first_name.required' => 'نام الزامی است.',
            'first_name.string' => 'نام نامعتبر است.',
            'first_name.max' => 'نام طولانی  تر از حد مجاز است.',
            'last_name.required' => 'نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
            'last_name.max' => 'نام خانوادگی طولانی  تر از حد مجاز است.',
            'password.string' => 'رمز عبور نامعتبر است.',
            'shaba_code.string' => 'شماره شبا نامعتبر است.',
            'shaba_code.max' => 'شماره شبا طولانی تر از حد مجاز است.',
            'email.string' => 'ایمیل نامعتبر است.',
            'email.email' => 'ایمیل نامعتبر است.'
        ]);
        $validator->after(function($validator)use($request , $user){
            $emailExists = User::where('email' , '=' , $request->email)
                ->where('id' , '!=' , $user->id)
                ->exists();
            if($emailExists){
                $validator->errors()->add('email' , 'ایمیل وارد شده از قبل توسط کاربران دیگر به ثبت رسیده است.');
            }
        });
        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors()->all())->withInput();
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        if($request->filled('password')){
            $user->password = Hash::make($request->password);
        }
        $user->shaba_code = $request->shaba_code;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success_msg' , 'اطلاعات کاربر با موفقیت ویرایش شد.');
    }

    public function showGuidInApp(){
        return view('app.guid');
    }

    public function getUserWithAjaxForAdsFilterSection(Request $request){
        $this->validate($request, [
            'q' => 'required|string|max:255'
        ]);
        $selectUsers = User::orderBy('first_name', 'asc')
            ->join('store' , 'store.user_id' , '=' , 'users.id')
            ->select('users.first_name' , 'users.last_name' , 'users.mobile' , 'users.id')
            ->groupBy('users.first_name' , 'users.last_name' , 'users.mobile' , 'users.id')
            ->where(function ($query) use ($request) {
                $query->whereRaw('( MATCH(users.first_name , users.last_name) AGAINST("'. $request->q .'") )')
                    ->orWhere('users.mobile' , 'like' , "%". $request->q ."%");
            })
            ->get();
        return response()->json($selectUsers);
    }
}
