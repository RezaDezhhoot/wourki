<?php

namespace App\Http\Controllers\API;

use App\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SoapClient;

class ForgetPassApi extends Controller
{
    public function sendResetLinkMobile(Request $request)
    {
        $user = User::where('mobile', '=', $request->mobile)->first();
        if ($user){
            $rnd = rand(11111, 99999);
            $user->verify_forget_password_token = $rnd;
            $user->save();

            $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $request->mobile . "&token=" . $rnd . "&template=verifyAccount";
            $client = new Client();
            $client->get($url);
                return response()->json(['user' => 200] , 200);
            }
            else{
            return response()->json(['user' => 400] , 400);
        }
    }

    public function changePassword(Request $request)
    {
        $user = User::where('mobile', '=', $request->mobile)
            ->where('verify_forget_password_token' , '=' , $request->verify_forget_password_token)
            ->first();
        if ($user){
            $token = $user->createToken('MyApp')->accessToken;
            $user->password = bcrypt($request->password);
            $user->gcm_code = $request->gcm_code;
            $user->verify_forget_password_token = null;
            $user->save();
            $user->token = $token;
//            dd($user);
            return response()->json($user , 200);
        }else{
            return response()->json('' , 400);
        }
    }
}
