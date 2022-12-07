<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Chat;
use App\Exports\UsersMobileExport;
use App\Http\Requests\web\registerUserInAdminRequest;
use App\Libraries\Swal;
use App\Marketer;
use App\Products;
use App\ReagentCode;
use App\Setting;
use App\Store;
use App\User;
use App\UserReferes;
use Carbon\Carbon;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class UserController extends Controller
{
    public function showList(Request $request)
    {
        $users = User::select('users.*', 'store.id as store_exists', 'province.name as province_name', 'city.name as city_name')
            ->addSelect(DB::raw('(
                select count(*)
                from reagent_code
                where reagent_code.reagent_code = users.reagent_code and
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
            ->leftJoin('address', 'address.id', '=', 'store.address_id')
            ->leftJoin('city', 'city.id', '=', 'address.city_id')
            ->join('province', 'province.id', '=', 'city.province_id')
            ->leftJoin('reagent_code', 'reagent_code.user_id', '=', 'users.id')
            ->leftJoin('users as referrer_user', 'referrer_user.mobile', '=', 'reagent_code.reagent_code')
            ->addSelect('referrer_user.first_name as referrer_first_name')
            ->addSelect('referrer_user.last_name as referrer_last_name')
            ->groupBy('users.mobile'
            // , 'users.first_name', 'users.last_name', 'users.password', 'users.mobile', 'users.mobile_confirmed', 'users.verify_mobile_token', 'users.shaba_code', 'users.gcm_code', 'users.reset_password_token', 'users.email', 'users.remember_token', 'users.verify_forget_password_token', 'users.banned', 'users.reagent_code', 'users.become_marketer',
            //     'users.created_at', 'users.updated_at', 'province.name', 'city.name', 'store.id', 'referrer_user.first_name', 'referrer_user.last_name'
            //     , 'users.last_login_datetime', 'users.referrer_user_id',
            //     'users.created_at', 'users.updated_at', 'referrer_user.first_name', 'referrer_user.last_name'
            //     , 'store.id', 'province.name', 'city.name', 'users.last_login_datetime', 'users.referrer_user_id'
            );
        if ($request->has('name') && !empty($request->name)) {
            $users->whereRaw('( MATCH(users.first_name , users.last_name) AGAINST("' . $request->name . '" IN NATURAL LANGUAGE MODE) )')
                ->addSelect(DB::raw('(
                      MATCH(users.first_name , users.last_name) AGAINST("' . $request->name . '" IN NATURAL LANGUAGE MODE)
                 ) as relevance'))
                ->orderBy('relevance', 'desc');
        } else {
            $users->orderBy('created_at', 'desc');
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
        $data['users'] = $list;

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
        $list = $users->paginate(15);
        $data['users'] = $list;
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
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ], [
            'first_name.required' => 'نام الزامی است.',
            'first_name.string' => 'نام نامعتبر است.',
            'last_name.required' => 'نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
        ]);
        auth()->guard('web')->user()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
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



    public function exportAllMobileExcel()
    {
        return Excel::download(new UsersMobileExport(), 'marketers.xlsx');
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

    public function getUserInfoById(Request $request, User $user)
    {
        return $user;
    }

    public function quickUpdate(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'password' => 'nullable|string',
            'shaba_code' => 'nullable|string|max:30',
            'email' => 'nullable|string|email'
        ], [
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
        $validator->after(function ($validator) use ($request, $user) {
            $emailExists = User::where('email', '=', $request->email)
                ->where('id', '!=', $user->id)
                ->exists();
            if ($emailExists) {
                $validator->errors()->add('email', 'ایمیل وارد شده از قبل توسط کاربران دیگر به ثبت رسیده است.');
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->all())->withInput();
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->shaba_code = $request->shaba_code;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success_msg', 'اطلاعات کاربر با موفقیت ویرایش شد.');
    }
    public function sharePage(){
        $user = auth()->guard('web')->user();
        $lists = UserReferes::where('user_refers.referrer_user_id' , $user->id)->join('users' , 'user_refers.referred_mobile_number' , '=' , 'users.mobile')->paginate(15);
        $stores = ReagentCode::where('type' , 'create_store')->where('reagent_code.reagent_code' , $user->reagent_code)
            ->join('users' , 'reagent_code.reagent_code' , '=' , 'users.reagent_code')
            ->join('store' , 'reagent_code.user_id' ,'=' , 'store.user_id')
            ->where('store.status' , 'approved')
            ->select('store.*')
            ->paginate(15);
        $setting = Setting::first();
        $whatsapp_text = str_replace(['%code%' , '%next_line%'] , [$user->reagent_code , '%0a'] , $setting->share_text);
        $telegram_text = str_replace(['%code%' , '%next_line%'] , [$user->reagent_code , '%0a'] , $setting->share_text);
        return view('frontend.my-account.share.index' , compact('lists' , 'user' , 'stores' , 'whatsapp_text' , 'telegram_text'));
    }
    public function loginByAdmin(Request $request,$user_id){
        $user = User::find($user_id);
        if(!$user){
            Swal::error('خطا','کاربر مورد نظر پیدا نشد');
            return redirect()->back();
        }
        try{
        // logging out
        auth()->guard('web')->logout();
        Cookie::queue(Cookie::forget('X_AJAX_TOKEN'));
        // logging in
        auth()->guard('web')->loginUsingId($user_id);
        $token = $user->createToken('MyApp')->accessToken;
        $user->save();
        Cookie::queue('X_AJAX_TOKEN', $token, 60 * 60 * 24 * 30);
        return redirect()->route('user.profile');
        }
        catch(Throwable $e){
            Swal::error('خطا', $e->getMessage());
            return redirect()->back();
        }
    }
    public function deleteUserPage(){
        return view('admin.users.delete');
    }
    public function deleteUser(Request $request){
        $request->validate([
            'mobile' => 'required|string'
        ] , [
            'mobile.required' => 'لطفا شماره موبایل را وارد نمایید'
        ]);
        $user = User::where('mobile' , $request->mobile)->first();
        if(!$user){
            Swal::error('خطا' , 'کاربر مورد نظر یافت نشد');
            return back();
        }
        $user_id = $user->id;
        DB::beginTransaction();
        try{
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            //deleting chats
            Chat::where('sender_id' , $user_id)->orWhere('receiver_id' , $user_id)->delete();
            
            $user->delete();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();
            Swal::success('موفق','کاربر مورد نظر با موفقیت حذف شد. چنانچه زمانی نیاز به بازیابی این کاربر دارید لطفا این کد را بادداشت نمایید : ' . $user_id);
            return back();
        }
        catch(Throwable $e){
            DB::rollBack();
            Swal::error('خطا', $e->getMessage());
            return back();
        }
    }
    public function getUserInAdmin(Request $request , $user_id){
        $user = User::find($user_id);
        if(!$user){
            abort(404);
        }
        return view('admin.users.single' , compact('user'));
    }
}
