<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Report;
use Illuminate\Http\Request;

class ReportApi extends Controller
{
    public function store(Request $request)
    {
        Report::create([
            'user_id' => auth()->guard('api')->user()->id,
            'store_id' => $request->store_id,
            'text' => $request->text,
            'visible' => 0,
        ]);
        return response()->json(['status' => 200], 200);
    }
}
