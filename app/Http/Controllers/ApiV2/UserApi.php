<?php

namespace App\Http\Controllers\ApiV2;

use App\Events\UserVerifyMobile;
use App\Marketer;
use App\ReagentCode;
use App\Setting;
use App\User;
use App\UserReferes;
use App\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserApi extends Controller
{
    public function verifyUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',
            'verify_code' => 'required'
        ], [
            'mobile.required' => 'تلفن همراه الزامی است.',
            'password.required' => 'رمز عبور الزامی است.',
            'verify_code.required' => 'کد احراز هویت الزامی است.'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorsArr = [];
            foreach ($errors as $error) {
                $obj = new \stdClass();
                $obj->error = $error;
                $errorsArr[] = $obj;
            }
            return response()->json($errorsArr, 400);
        }
        $mobile = $request->mobile;
        $verifyCode = $request->verify_code;
        $password = $request->password;

        $user = User::where('mobile', '=', $mobile)->first();

        if (!$user) {
            return response()->json(['error' => 'کاربری با این شماره تلفن همراه یافت نشد.'], 404);
        }

        if ($user->mobile_confirmed) {
            return response()->json($user, 200);
        }

        if (!Hash::check($password, $user->password)) {
            return response()->json(['error' => 'رمز عبور صحیح نمیباشد.'], 404);
        }

        if ($user->verify_mobile_token == $verifyCode) {
            $user->mobile_confirmed = 1;
            $user->verify_mobile_token = null;
            $user->register_from = "android";
            $user->save();
            event(new UserVerifyMobile($user));
            $token = $user->createToken('MyApp')->accessToken;
            $user->token = $token;
            $user->verify_mobile_token = null;
            $user->verify_email_token = null;
            $user->reset_password_token = null;

            return response()->json($user, 200);
        }
        return response()->json(['is_verify' => false,'error' => 'کد احراز هویت وارد شده نامعتبر است.'], 200);
    }
    public function share(Request $request){
        try{
        $user = User::find(auth()->guard('api')->user()->id);
        $lists = UserReferes::where('user_refers.referrer_user_id', $user->id)->join('users', 'user_refers.referred_mobile_number', '=', 'users.mobile')->paginate(15);
        $stores = ReagentCode::where('type', 'create_store')->where('reagent_code.reagent_code', $user->reagent_code)
            ->join('users', 'reagent_code.reagent_code', '=', 'users.reagent_code')
            ->join('store', 'reagent_code.user_id', '=', 'store.user_id')
            ->where('store.status', 'approved')
            ->select('store.*')
            ->paginate(15);
            $setting = Setting::first();
            $whatsapp_text = str_replace(['%code%', '%next_line%'], [$user->reagent_code, '%0a'], $setting->share_text);
            $telegram_text = str_replace(['%code%', '%next_line%'], [$user->reagent_code, '%0a'], $setting->share_text);
        return response()->json(['status' => 200,'refers' => $lists , 'stores' => $stores , 'whatsapp_text' => $whatsapp_text , 'telegram_text' => $telegram_text] , 200);
        }
        catch(Throwable $e){
            return response()->json(['status' => 400 , 'error' => $e->getMessage()] , 200);
        }
    }
}
