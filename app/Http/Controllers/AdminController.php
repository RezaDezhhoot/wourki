<?php

namespace App\Http\Controllers;

use App\Admin;
use App\BillItems;
use App\Libraries\Swal;
use App\Products;
use App\Store;
use App\WholeSeller;
use App\wholeSellerProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function doLogin(Request $request)
    {
        $this->validate($request , [
            'login' => 'required',
            'password' => 'required',
//            'g-recaptcha-response' => 'required|recaptcha',
        ] , [
            'login.required' => 'ایمیل یا شماره تلفن همراه الزامی است.',
            'password.required' => 'رمز عبور الزامی است.',
//            'g-recaptcha-response.required' => 'تیک زدن reCaptcha الزامی است.',
//            'g-recaptcha-response.recaptcha' => 'reCaptcha نامعتبر است.'
        ]);
        if (filter_var($request->login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } else {
            $field = 'mobile';
        }
        $password = $request->password;
        if ($request->has('remember_me')) {
            $remember = true;
        } else {
            $remember = false;
        }
        if (Auth::guard('admin')->attempt([$field => $request->login , 'password' => $password], $remember)) {
            return redirect()->route('AdminDashboard');
        } else {
            return redirect()->with('error_msg', 'اطلاعات ورود به سیستم اشتباه است. لطفا مجددا تلاش کنید.');
        }
    }

    public function showDashboard(Request $request)
    {
        return view('admin.dashboard');
    }

    public function doLogout(Request $request){
        Auth::guard('admin')->logout();
        return redirect()->route('showAdminLoginPage');
    }

    public function changePasswordForm()
    {
        return view('admin.profile.index');
    }

    public function changePassword(Request $request)
    {

        $this->validate($request ,[
            'password' => 'required|string|min:6',
        ], [
            'password.required' => 'رمز عبور الزامی است.',
            'password.string' => 'رمز عبور نامعتبر است.',
            'password.min' => 'پسورد حداقل 6  کاراکتر باید باشد.',
        ]);

        $admin = \auth()->guard('admin')->user();
        $password = bcrypt($request->password);
        $admin = Admin::where('id' , $admin->id)->first();
        $admin->password = $password;
        $admin->save();
        if ($admin){
            Swal::success('تغییر موفقیت آمیز', 'پسورد با موفقیت تغییر یافت');
            return redirect()->route('adminDashboard');
        }
    }
    public function statistics(Request $request){
        
    }
}
