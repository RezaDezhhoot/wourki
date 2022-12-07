<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Plan;
use Illuminate\Http\Request;

class PlanApi extends Controller
{
    public function index(Request $request)
    {
        $plans = Plan::query()->where('status' , 'show');
        if($request->has('plan_type')){
            $plans->where('type' , $request->plan_type);
        }
        $plans = $plans->get();
        return response()->json($plans , 200);
    }

}
