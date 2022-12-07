<?php

namespace App\Http\Controllers\API;

use App\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CityApi extends Controller
{
    public function show(Request $request)
    {
        $provinceId = $request->provinceId;
        $city = City::where('province_id' , $provinceId)
            ->where('deleted' , 0)->get();
        return response()->json($city , 200);
    }
}
