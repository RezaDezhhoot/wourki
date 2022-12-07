<?php

namespace App\Http\Controllers\API;

use App\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProvinceApi extends Controller
{
    public function show()
    {
        $province = Province::where('deleted' , 0)->get();
        return response()->json($province , 200);
    }
}
