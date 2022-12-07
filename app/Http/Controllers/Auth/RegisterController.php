<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserVerifyMobile;
use App\Helpers\ReagentCodeGenerator;
use App\Http\Controllers\Controller;
use App\Libraries\Swal;
use App\Marketer;
use App\ReagentCode;
use App\Setting;
use App\User;
use App\UserReferes;
use App\Wallet;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '';


    public function __construct()
    {

    }

    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function doRegister(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'password' => 'required|string|confirmed|max:255|min:6',
            'mobile' => 'required|numeric|unique:users|min:11',
            'email' => 'nullable|email|max:100',
            'reagent_code' => 'nullable|string|exists:users,reagent_code'
        ], [
            'first_name.required' => 'نام الزامی است.',
            'first_name.max' => 'نام نامعتبر است.',
            'last_name.required' => 'نام خانوادگی الزامی است.',
            'last_name.max' => 'نام خانوادگی نامعتبر است.',
            'password.required' => 'پسورد الزامی است.',
            'password.confirmed' => 'پسورد مطابقت ندارد.',
            'password.min' => 'پسورد حداقل 6 حرفی میباشد.',
            'email.email' => 'ایمیل نامعتبر است.',
            'mobile.required' => 'موبایل الزامی است.',
            'mobile.unique' => 'موبایل قبلا ثبت شده است.',
            'mobile.min' => 'حروف موبایل حداقل 11 کاراکتر است.',
            'reagent_code.exists' => 'کد معرف نامعتبر است.',
        ]);

        $rnd = rand(11111, 99999);
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->password = bcrypt($request->password);
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->verify_mobile_token = $rnd;
        $generator = new ReagentCodeGenerator();
        $user->reagent_code = $generator->generate();
        $refferedUserId = null;
        if($request->reagent_code){
        $refferedUserId = User::where('reagent_code', strtolower($request->reagent_code))->first()->id;
        $user->referrer_user_id = $refferedUserId;
        }
        $user->save();
        auth()->login($user);
        $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $request->mobile . "&token=" . $rnd . "&template=verifyAccount";
        $client = new Client();
        $client->get($url);

        return redirect()->route('verify.mobile.page');
    }

    public function verifyMobilePage(Request $request)
    {

        return view('frontend.auth.verify-account');
    }

    public function verifyMobile(Request $request)
    {

        $user = \auth()->guard('web')->user();
        $user = User::find($user->id);
        /*if ($request->cookie('pin_code')) {
            if ($user->verify_mobile_token == Cookie::get('pin_code')) {
                Cookie::queue('pin_code', $request->pin_code, 60);
                $user->update(['mobile_confirmed' => 1]);
                return redirect()->route('user.profile');
            } else {
                Swal::error('ناموفق :(', 'پین کد وارد شده اشتباه است.');
                if ($request->has('redirectTo'))
                    return redirect()->to($request->redirectTo);
                else
                    return back();
            }
        } else {*/
        $request->validate([
            'pin_code' => 'required|numeric|digits:5'
        ], [
        ], [
            'pin_code' => 'پین کد'

        ]);
        if ($user->verify_mobile_token == $request->pin_code) {
            if($user->mobile_confirmed){
                // do nothing
            }else{
                if ($user->referrer_user_id) {
                    $refer = new UserReferes();
                    $refer->referred_mobile_number = $user->mobile;
                    $refer->referrer_user_id = $user->referrer_user_id;
                    $refer->save();
                }
                event(new UserVerifyMobile($user));
                
            }
            
            // Cookie::queue('pin_code', $request->pin_code, 60);
            $user->update(['mobile_confirmed' => 1 , 'register_from' => 'website']);
            $token = $user->createToken('MyApp')->accessToken;
            Cookie::queue('X_AJAX_TOKEN', $token, 60 * 60 * 24 * 30);
            return redirect()->route('user.profile');
        } else {
            Swal::error('ناموفق', 'پین کد وارد شده اشتباه است.');

            return redirect()->back();
        }
//        }

    }

}
