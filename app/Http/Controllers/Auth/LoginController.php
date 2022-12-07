<?php

namespace App\Http\Controllers\Auth;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Libraries\Swal;
use App\User;
use Carbon\Carbon;
use Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('frontend/auth/login');
    }

    public function logout()
    {
        auth()->guard('web')->logout();
        Cookie::queue(Cookie::forget('X_AJAX_TOKEN'));
        return redirect()->route('mainPage');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string|min:11|exists:users,mobile',
            'password' => 'required|string|min:4',
        ] , [
            'mobile.required'   => 'موبایل الزامی است.' ,
            'mobile.string'     => 'موبایل نامعتبر است.' ,
            'mobile.min'        => 'موبایل حداکثر 11 عدد است.' ,
            'mobile.exists'     => 'کاربری با موبایل وارد شده یافت نشد.' ,
            'password.required' => 'پسورد الزامی است.' ,
            'password.string'   => 'پسورد نامعتبر است.' ,
            'password.min'      => 'حروف پسورد حداقل 4 کاراکتر است.' ,
        ]);

        $rememberMe = $request->has('remember') ? true : false;
        $user = User::where('mobile' , $request->mobile)->first();
        if ($user->banned == 0) {
            if (auth()->guard('web')->attempt(['mobile' => $request->mobile , 'password' => $request->password] , $rememberMe) && $user->mobile_confirmed) {
                $current_user  = \auth()->guard('web')->user();
                $current_user->last_login_datetime = Carbon::now()->toDateTimeString();
                $current_user->save();
                //creating token for ajax calls
                $user = User::find($current_user->id);
                $token = $user->createToken('MyApp')->accessToken;
                $user->save();
                Cookie::queue('X_AJAX_TOKEN' , $token , 60 * 60 * 24 * 30);
                if ($request->has('redirectTo'))
                    return redirect()->to(\request()->redirectTo);
                else
                    return redirect()->route('user.profile');
            } else {
                Swal::error('ناموفق!', 'موبایل یا پسورد اشتباه میباشد، مجددا تلاش کنید.');
                return back();
            }
        } else {
            Swal::error('ناموفق!', 'حساب کاربری شما مسدود میباشد. جهت خروج از حالت مسدود با مدیر سایت تماس حاصل فرمایید.');
            return back();
        }

    }
}
