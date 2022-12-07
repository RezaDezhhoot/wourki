<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Libraries\Swal;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showResetForm(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|digits:11|exists:users,mobile',
            'token' => 'required|digits:5'
        ], [
            'mobile.required' => 'وارد کردن تلفن همراه الزامی است.',
            'mobile.digits' => 'شماره تلفن همراه نامعتبر است.',
            'mobile.exists' => 'کاربری با این تلفن همراه یافت نشد',
            'token.required' => 'ارسال کد فراموشی رمز عبور الزامی است.',
            'token.digits' => 'کد فراموشی رمز عبور نامعتبر است.'
        ]);
        $user = User::where('mobile' , $request->mobile)->where('verify_forget_password_token' , $request->token)->first();
        if(!$user){
            Swal::error('خطا', 'کد فراموشی رمز عبور نادرست است.');
            return redirect()->back();
        }

        $data['mobile'] = $request->mobile;
        $data['token'] = $request->token;
        return view('frontend.auth.reset-password')->with($data);
    }

    public function checkToken(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|digits:11|exists:users,mobile',
            'token' => 'required|digits:5'
        ], [
            'mobile.required' => 'وارد کردن تلفن همراه الزامی است.',
            'mobile.digits' => 'شماره تلفن همراه نامعتبر است.',
            'mobile.exists' => 'کاربری با این تلفن همراه یافت نشد',
            'token.required' => 'ارسال کد فراموشی رمز عبور الزامی است.',
            'token.digits' => 'کد فراموشی رمز عبور نامعتبر است.'
        ]);
        $user = User::where('mobile', $request->mobile)->first();
        if ($user->verify_forget_password_token == $request->token) {
            return redirect()->route('reset.password.form.show' , ['mobile' => $request->mobile , 'token' => $request->token]);
        } else {
            Swal::error('خطا', 'کد فراموشی رمز عبور نادرست است.');
            return redirect()->back();
        }
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request ,[
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'رمز عبور الزامی است.',
            'password.string' => 'رمز عبور نامعتبر است.',
            'password.min' => 'پسورد حداقل 6  کاراکتر باید باشد.',
            'password.confirmed' => 'پسورد مطابقت ندارد.',
        ]);

        $password = \request()->input('password');
        $user = User::where('mobile' , $request->mobile)
            ->where('verify_forget_password_token' , $request->token)
            ->first();
        if ($user) {
            $user->password = Hash::make($password);
            $user->save();
            auth()->login($user);
            Swal::success('تغییر موفقیت آمیز کلمه عبور', 'تغییر کلمه عبور با موفقیت انجام شد.');
//            return redirect()->route('showUserProfile');
        }else{
            return redirect()->back();
        }

    }
}
