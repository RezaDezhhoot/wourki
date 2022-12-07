<?php

namespace App\Http\Controllers\API;

use App\Attribute;
use App\Http\Controllers\Controller;

class AttributeApi extends Controller
{
    public function index()
    {
        $attribute = Attribute::all();
        return response()->json($attribute , 200);
    }

}
