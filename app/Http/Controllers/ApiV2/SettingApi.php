<?php

namespace App\Http\Controllers\ApiV2;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingApi extends Controller
{
    public function appVersionShow(){
        $setting = Setting::first();
        return response()->json([
            'status' => 200,
            'message' => 'app_version_returned',
            'entire' => [
                $setting
            ]
        ]);
    }
}
