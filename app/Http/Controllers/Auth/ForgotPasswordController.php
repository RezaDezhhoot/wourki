<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\Swal;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showForgetPasswordForm()
    {
        return view('frontend.auth.forget-password');
    }

    public function forgetPassword(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:11'
        ] , [
            'mobile.required' => 'موبایل الزامی است.' ,
            'mobile.digits' => 'موبایل وارد شده باید بصورت عددی و 11 عدد باشد.' ,
        ]);

        $user = User::where('mobile','=', $request->mobile)->first();
        if ($user->exists()) {
            $rnd = rand(11111, 99999);
            $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $user->mobile . "&token=" . $user->verify_mobile_token . "&template=verifyAccount";
            $client = new Client();
            $client->get($url);
            $user->verify_mobile_token = $rnd;
            $user->save();
            return view('frontend.auth.reset-password-2' , compact('user'));
        } else {
            Swal::error('ناموفق :(', 'کاربری با شماره موبایل وارد شده یافت نشد.');
            return back();
        }
    }

    public function authUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric|exists:users,id' ,
            'pin_code' => 'required|digits:5' ,
            'password' => 'required|string|confirmed|min:6' ,
        ] , [
            'pin_code.required' => 'پین کد الزامی است.' ,
            'pin_code.digits' => 'پین باید بصورت عددی و 5 رقم باشد.' ,
            'password.required' => 'پسورد الزامی است.',
            'password.confirmed' => 'پسورد مطابقت ندارد.',
            'password.min' => 'پسورد حداقل 6 حرفی میباشد.',
        ]);
        $user = User::where('id' , $request->user_id)->first();
        if ($user->verify_mobile_token == $request->pin_code) {
            $user->update(['password' => bcrypt($request->password)]);
            Swal::success('موفقیت آمیز.', 'پسورد با موفقیت ویرایش شد.');
            auth()->login($user);
            return redirect()->route('user.profile');
        } else {
            Swal::error('ناموفق.', 'پین کد وارد شده اشتباه است.');
            return view('frontend.auth.reset-password-2' , compact('user'));
        }
    }
}
