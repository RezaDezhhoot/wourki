<?php

namespace App\Http\Controllers;

use App\Marketer;
use App\ReagentCode;
use App\Setting;
use App\User;
use App\UserReferes;
use App\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use PDO;

class ReagentCodeController extends Controller
{
    public function index(Request $request, User $user)
    {
        $pdo = DB::connection()->getPdo();
        $pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        $lists = ReagentCode::join('users as referred_user', 'referred_user.id', '=', 'reagent_code.user_id')
            ->join('users as referrer', 'referrer.reagent_code', '=', 'reagent_code.reagent_code')
            ->leftJoin('marketer', 'marketer.user_id', '=', 'referrer.id')
            ->select('referrer.first_name as referrer_first_name', 'referrer.last_name as referrer_last_name', 'reagent_code.reagent_user_fee',
                'referred_user.first_name as referred_first_name', 'referred_user.last_name as referred_last_name', 'reagent_code.reagented_user_fee',
                'reagent_code.type', 'marketer.user_id as is_marketer', 'reagent_code.created_at')
            ->groupBy('referrer.first_name', 'referrer.last_name', 'referred_user.last_name', 'reagent_code.reagent_user_fee', 'referred_user.first_name', 'referred_user.last_name',
                'reagent_code.reagented_user_fee', 'reagent_code.type', 'marketer.user_id', 'reagent_code.created_at');
//        $lists = ReagentCode::join('users' , 'users.id' , '=' , 'reagent_code.user_id')
//            ->select('users.*' , 'reagent_code.*' , 'users.id as user_id')
//            ->addSelect(DB::raw('(
//                select users.id
//                from users
//                where reagent_code.reagent_code = users.reagent_code
//            ) as reagent_user_id'))
//            ->addSelect(DB::raw('(
//                select concat(first_name , " " , last_name)
//                from users
//                where users.reagent_code = reagent_code.reagent_code
//            ) as reagent_user'));

//        if ($user) {
//            $lists->where('reagent_code.reagent_code' , $user->reagent_code);
//        }
        if ($request->has('user_type')) {
//            $marketerUsers = Marketer::join('users' , 'users.id' , '=' , 'marketer.user_id')
//                ->pluck('users.reagent_code')
//                ->toArray();
            if ($request->user_type == 'marketer') {
                $lists->whereNotNull('marketer.user_id');
//                $lists->whereIn('reagent_code.reagent_code' , $marketerUsers);
            } else {
                $lists->whereNull('marketer.user_id');
//                $lists->whereNotIn('reagent_code.reagent_code' , $marketerUsers);
            }
        }
        if ($request->has('reagent_type')) {
            if ($request->reagent_type == 'reagent') {
                $lists->where('reagent_code.type', 'reagent');
            } else {
                $lists->where('reagent_code.type', 'create_store');
            }
        }
        if ($request->filled('user_mobile')) {
            $lists->where('referrer.mobile', 'like', "%" . $request->user_mobile . "%");
        }
        if ($request->filled('user')) {
            $reagent = User::find($request->user);
            $lists->where('reagent_code.reagent_code', $reagent->mobile);
        }
        $lists = $lists->paginate(15)->appends([
            'reagent_type' => $request->reagent_type,
            'user_type' => $request->user_type,
            'marketer' => $request->marketer,
            'user' => $request->user
        ]);
        $users = User::join('reagent_code', 'reagent_code.reagent_code', 'users.reagent_code')
            ->where('reagent_code.type', '!=', 'create_store')
            ->select('users.id as user_id', 'users.first_name', 'users.last_name')
            ->groupBy('users.id', 'users.first_name', 'users.last_name')
            ->get();

        $setting = Setting::first();
        if (auth()->guard('admin')->check() && Route::currentRouteName() == 'list.of.reagent.code.user')
            return view('admin.reagent_code.index', compact('lists', 'users'));
        elseif (auth()->guard('web')->check() && Route::currentRouteName() == 'list.of.reagent.code.user1')
            return view('frontend.my-account.marketer.index', compact('lists', 'user', 'setting'));
    }

    public function userReagented(User $user)
    {
        $lists = ReagentCode::join('users', 'users.id', '=', 'reagent_code.user_id')
            ->where('reagent_code.type', '!=', 'create_store')
            ->where('users.id' , '=' , $user->id)
            ->paginate(15);

        return view('admin.reagent_code.userReagented', compact('lists'));
    }

    public function userWallet()
    {
        $user = auth()->guard('web')->user();
        $lists = Wallet::join('users', 'users.id', '=', 'wallet.user_id')
            ->where('wallet.user_id', $user->id)
            ->select('users.*', 'wallet.*')
            ->addSelect(DB::raw('(
                select count(*)
                from marketer
                where marketer.id = users.id
            ) as marketer'))
            ->orderByDesc('wallet.created_at')
            ->paginate(15);
        $sumPrice = Wallet::where('user_id', $user->id)
            ->sum('cost');
        $helpText = Setting::first()->wallet_page_help_text;
        return view('frontend.my-account.wallet.index', compact('lists', 'sumPrice', 'helpText'));
    }

    public function delete(ReagentCode $reagent)
    {
        try {
            $reagent->delete();
            return back();
        } catch (\Exception $exception) {
            return back();
        }
    }

    public function refer(Request $request)
    {
        if($request->filled('phone')){
            $userExists = User::where('mobile' , $request->phone)->exists();
            if($userExists){
                $request->session()->put('referrer_mobile_number' , $request->phone);
                $request->session()->forget('mobile_number_nof_found');
                return redirect()->route('web.refer');
            }
            $request->session()->forget('referrer_mobile_number');
            $request->session()->put('mobile_number_nof_found' , 'متاسفانه کاربر معرف یافت نشد!');
            return redirect()->back();
        }else{
            if($request->session()->has('referrer_mobile_number')){
                $request->session()->forget('mobile_number_nof_found');
                return view('refer');
            }else{
                return view('refer')->with('mobile_number_nof_found' , 'متاسفانه کاربر معرف یافت نشد!');
            }
        }
    }

    public function saveScore(Request $request){
        $this->validate($request , [
            'mobile' => 'required|digits:11|unique:users,mobile'
        ] , [
            'mobile.required' => 'تلفن همراه الزامی است.',
            'mobile.digits' => 'تلفن همراه باید دقیقا 11 رقم باشد.',
            'mobile.unique' => 'تلفن همراه وارد شده از قبل ثبت شده است.'
        ]);
        $mobile = $request->mobile;
        $ref = new UserReferes();
        $ref->referrer_user_id = User::where('mobile' , $request->session()->get('referrer_mobile_number') )
            ->first()->id;
        $ref->referred_mobile_number = $mobile;
        $ref->save();
        return redirect()->route('web.show_download_links');
    }

    public function showDownloadLinks(){
        return view('show-download-links');
    }

}
