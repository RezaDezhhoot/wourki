<?php

namespace App\Http\Controllers\API;

use App\_productSellerFavorite;
use App\Events\UserVerifyMobile;
use App\Helpers\ReagentCodeGenerator;
use App\Http\Controllers\Controller;
use App\Http\Requests\api\users\register;
use App\Marketer;
use App\ProductPhoto;
use App\ReagentCode;
use App\Setting;
use App\User;
use App\UserReferes;
use App\Wallet;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class UserApi extends Controller
{
    public function forceLogoutBannedUsers(){
        $user = auth()->guard('api')->user();
        if($user->banned == 1){
            return response()->json([
                'status' => 400
            ]);
        }
        return response()->json([
            'status' => 200
        ]);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|string|max:200',
            'last_name' => 'required|string|max:200',
            'mobile' => 'required|string|max:200|unique:users,mobile',
            'password' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'gcm_code' => 'required',
            'reagent_code' => 'nullable|string|exists:users,reagent_code'
        ],[
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'first_name.string' => 'نام نامعتبر است.',
            'first_name.max' => 'نام طولانی تر از حد مجاز است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
            'last_name.max' => 'نام خانوادگی طولانی تر از حد مجاز است.',
            'mobile.required' => 'وارد کردن تلفن همراه الزامی است.',
            'mobile.string' => 'تلفن همراه نامعتبر است.',
            'mobile.max' => 'تلفن همراه طولانی تر از حد مجاز است.',
            'mobile.unique' => 'تلفن همراه وارد شده از قبل ثبت شده است.',
            'password.required' => 'رمز عبور الزامی است.',
            'password.string' => 'رمز عبور نامعتبر است.',
            'email.email' => 'ایمیل معتبر نیست.',
            'gcm_code.required' => 'کد gcm الزامی است.',
            'reagent_code.exists' => 'کد معرف نامعتبر است.',
        ]);
        if($validator->fails()){
            return response()->json(['status' => 400 , 'errors' => $validator->errors()->all()] , 400);
        }
        try{
        $rnd = rand(11111, 99999);
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        $user->mobile_confirmed = '0';
        $user->verify_mobile_token = $rnd;
        $user->gcm_code = $request->gcm_code;
        $user->email = $request->email;
        $generator = new ReagentCodeGenerator();
        $user->reagent_code = $generator->generate();
        if($request->reagent_code && !is_null($request->reagent_code && $request->reagent_code != "")){
        $ref =  User::where('reagent_code', strtolower($request->reagent_code))->first();
        if(!$ref){
            return response()->json(['status' => 400 , 'errors' => ['کاربر معرف یافت نشد!']],200);
        }
        $refferedUserId = $ref->id;
        $user->referrer_user_id = $refferedUserId;
        }
        $user->save();
        $user->verify_mobile_token = null;
        $user->verify_email_token = null;
        $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $user->mobile . "&token=" . $rnd . "&template=verifyAccount";

        $client = new Client();
        $client->get($url);
        return response()->json($user, 201);
        }
        catch(\Exception $e){
            return response()->json(['status' => 400 , 'error' => $e->getMessage()] , 400);
        }
    }

    public function login(Request $request)
    {
        $mobile = $request->mobile;
        $password = $request->password;
        $gcm_code = $request->gcm_code;


        $user = User::where('mobile', $mobile)->first();
        if(!$user){
            return response()->json([
                'status' => 404,
                'error' => 'حسابی با این شماره تلفن یافت نشد'
            ]);
        }
        if ($user && $user->banned == 1) {
            return response()->json([
                'status' => 402,
                'error' => 'حساب کاربری شما مسدود شده است.'
            ], 402);
        }
        if(!$user->mobile_confirmed){
            return response()->json([
                'sttaus' => 402,
                'error' => 'حساب کاربری شما تایید نشده است.',
                'is_verify' => false,
                'mobile_confirmed' => 0
            ], 200);
        }
        if ($user) {
            if (Hash::check($password, $user->password)) {
                $token = $user->createToken('MyApp')->accessToken;
                $user->gcm_code = $gcm_code;
                $user->last_login_datetime = Carbon::now()->toDateTimeString();
                $user->save();
                $user->token = $token;
                $user->thumbnail_photo = url()->to('image/store_photos').'/'.$user->thumbnail_photo;
                return response()->json($user, 200);
            } else {
                return response()->json(['error' => 'تلفن همراه یا رمز عبور وارد شده اشتباه است.'], 401);
            }
        }
        return response()->json(['error' => 'تلفن همراه یا رمز عبور وارد شده اشتباه است.'], 401);
    }

    public function resendVerifyCode(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|digits:11'
        ]);
        $user = User::where('mobile', $request->mobile)->first();
        $user->verify_mobile_token = rand(11111, 99999);
        $user->save();

        $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $user->mobile . "&token=" . $user->verify_mobile_token . "&template=verifyAccount";

        $client = new Client();
        $client->get($url);

        return response()->json([
            'status' => 200
        ]);
    }

    public function verifyMobile(Request $request)
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
            if ($user->referrer_user_id) {
                $refer = new UserReferes();
                $refer->referred_mobile_number = $user->mobile;
                $refer->referrer_user_id = $user->referrer_user_id;
                $refer->save();
            }
            $user->verify_mobile_token = null;
            $user->save();
            event(new UserVerifyMobile($user));
            $token = $user->createToken('MyApp')->accessToken;
            $user->token = $token;
            $user->verify_mobile_token = null;
            $user->verify_email_token = null;
            $user->reset_password_token = null;

            // gifting is done in event so below code is commented out
            // $setting = Setting::first();
            // $reagentUser = User::where('id', $user->referrer_user_id)->where('banned', 0)->first();
            // if($reagentUser){
            //     Wallet::create([
            //         'user_id' => $user->id,
            //         'cost' => $setting->reagent_user_fee,
            //         'wallet_type' => 'reagented'
            //     ]);
            //     if (!Marketer::where('user_id', $reagentUser->id)->exists()) {
            //         Wallet::create([
            //             'user_id' => $reagentUser->id,
            //             'cost' => $setting->reagented_user_fee,
            //             'wallet_type' => 'reagent',
            //         ]);
            //         $reagent_fee = $setting->reagent_user_fee;
            //         $checkout = 1;
            //     } else {
            //         $reagent_fee = $setting->marketer_fee;
            //         $checkout = 0;
            //     }


            //     ReagentCode::create([
            //         'user_id' => $user->id,
            //         'reagent_code' => $reagentUser->reagent_code,
            //         'reagent_user_fee' => $reagent_fee,
            //         'reagented_user_fee' => $setting->reagented_user_fee,
            //         'type' => 'reagent',
            //         'checkout' => $checkout,
            //     ]);
            // }


            return response()->json($user, 200);
        }
        return response()->json(['error' => 'کد احراز هویت وارد شده نامعتبر است.'], 401);
    }

    public function showFav(Request $request)
    {
        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : 1;

        $user = auth()->guard('api')->user();
        $fav_product = new _productSellerFavorite();
        $favUser = $fav_product->dbSelect(_productSellerFavorite::FIELDS)
            ->where('fav_product.user_id', '=', $user->id)
            ->where('product.deleted', '=', 0)
            ->offset($offset)
            ->limit($limit)
            ->get();

        $favList = collect($favUser);

        foreach ($favList as $index => $row) {
            $photos = ProductPhoto::where('product_id', $row->id)->get();
            foreach ($photos as $i => $photoItem) {
                $photos[$i]->name = URL::to('/image/product_photos') . '/' . $photoItem->name;
            }
            $created_at_fa = jDate($row->created_at)->format('date');
            $favList[$index]->created_at = $created_at_fa;
            $updated_at_fa = jDate($row->updated_at)->format('date');
            $favList[$index]->updated_at = $updated_at_fa;
            $favList[$index]->photos = $photos;
        }

        if (count($favList) > 0) {
            return response()->json($favList, 200);
        } else {
            return response()->json([ "status" => 200], 200);
        }

    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'about' => 'string'
        ], [
            'first_name.required' => 'نام الزامی است',
            'last_name.required' => 'نام خانوادگی الزامی است',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $str = "";
            foreach ($errors as $error) {
                $str = $str . "," . $error;
            }
            return response()->json(['error' => $str], 400);
        }

        $user = auth()->guard('api')->user();
        $firstName = $request->first_name;
        $lastName = $request->last_name;
        $userQ = User::where('id', $user->id)->first();
        $userQ->first_name = $firstName;
        $userQ->last_name = $lastName;
        $userQ->about = $request->about;
        $userQ->save();
        if ($userQ) {
            return response()->json($userQ, 200);
        } else {
            return response()->json(['error' => 'ویرایش انجام نشد'], 400);
        }
    }

    public function changePassword(Request $request)
    {
        $user = auth()->guard('api')->user();
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['status' => 200], 200);
    }

    public function changeMobile(Request $request)
    {
        $newMobile = $request->newMobile;
        $oldMobile = $request->oldMobile;
        $password = $request->password;
        $rnd = rand(11111, 99999);

        if (User::where('mobile', $newMobile)->exists()) {
            return response()->json(['status' => 401], 200);
        } else {
            $user = User::where('mobile', $oldMobile)->first();
            if ($user) {
                if (Hash::check($password, $user->password)) {
                    $user->update([
                        'mobile' => $newMobile,
                        'verify_mobile_token' => $rnd,
                    ]);
                    $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $newMobile . "&token=" . $rnd . "&template=verifyAccount";
                    $client = new Client();
                    $client->get($url);
                    return response()->json(['status' => 200], 200);
                } else
                    return response()->json(['status' => 400], 200);
            } else
                return response()->json(['status' => 400], 200);
        }
    }

    public function userShabaCode()
    {
        $user = auth()->guard('api')->user();
        return response()->json(['shaba-code' => $user->shaba_code], 200);
    }

    public function editShabCode(Request $request)
    {
        $user = auth()->guard('api')->user();
        $user->shaba_code = $request->shaba_code;
        $user->save();
        return response()->json(['status' => 200], 200);
    }

    public function becomeMarketer()
    {
        $user = User::find(auth()->guard('api')->user()->id);
        if ($user->become_marketer == 0) {
            $user->update(['become_marketer' => 1]);
            return response()->json(['status' => 200], 200);
        } else
            return response()->json(['status' => 200], 201);
    }
    public function profilePhoto(Request $request){
        $validated = Validator::make($request->all() , [
            'image' => 'required|file|image|max:512'
        ] , [
            'image.required' => 'لطفا تصویر نمایه را انتخاب کنید',
            'image.max' => 'حداکثر حجم تصویر 512 کیلوبایت میتواند باشد'
        ]);
        if($validated->fails()){
            return response()->json(['status' => 400 , 'errors' => $validated->errors()->all()] , 200);
        }
        $user = User::find(auth()->guard('api')->user()->id);
        $imgName = uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('image/store_photos'), $imgName);
        $user->thumbnail_photo = $imgName;
        $user->save();
        unset($user->mobile_confirmed);
        unset($user->verify_mobile_token);
        unset($user->reset_password_token);
        $user->thumbnail_photo = url()->to('image/store_photos'). '/' . $imgName;
        return response()->json(['user' => $user] , 200);
    }
    
}
