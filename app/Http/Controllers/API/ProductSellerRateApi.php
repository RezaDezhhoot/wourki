<?php

namespace App\Http\Controllers\API;

use App\ProductRate;
use App\ProductSeller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductSellerRateApi extends Controller
{
    public function rate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric|min:1|max:5',
            'product_seller_id' => 'required|numeric|exists:product_seller,id'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'validation_error_occurred',
                'entire' => [
                    'errors' => $validator->errors()->all()
                ]
            ]);
        }
        $user = auth()->guard('api')->user();
        ProductRate::updateOrCreate([
            'user_id' => $user->id,
            'product_seller_id' => $request->product_seller_id
        ], [
            'rate' => $request->rate
        ]);
        $avgRate = ProductRate::where('product_seller_id', $request->product_seller_id)->avg('rate');


        return response()->json([
            'status' => 200,
            'message' => 'rate_successfully_saved',
            'rate' => $avgRate
        ]);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_seller_id' => 'required|numeric|exists:product_seller,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'validation_error_occurred',
                'entire' => [
                    'errors' => $validator->errors()->all()
                ]
            ]);
        }
        $user = auth()->guard('api')->user();
        if($user)
        $row = ProductRate::where('user_id' , $user->id)
            ->where('product_seller_id' , $request->product_seller_id)
            ->first();
        else {
            $row = null;
        }
        return response()->json([
            'status' => 200,
            'message' => 'rate_successfully_saved',
            'rate' => $row ? $row->rate : 0
        ]);

    }
}
