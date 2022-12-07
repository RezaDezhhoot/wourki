<?php

namespace App\Http\Controllers\API;

use App\Cart;
use App\Products;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ErrorTrackingApi extends Controller
{
    public function showErro(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'nullable|string',
            'address' => 'required|string',
            'postal_code' => 'required|numeric',
            'city_id' => 'required|numeric|exists:city,id'
        ], [
            'description.string' => 'توضیحات نامعتبر است.',
            'address.required' => 'آدرس الزامی است.',
            'address.string' => 'آدرس نامعتبر است.',
            'postal_code.required' => 'کدپستی الزامی است.',
            'postal_code.numeric' => 'کدپستی نامعتبر است.',
            'city_id.required' => 'شهر الزامی است.',
            'city_id.numeric' => 'شهر نامعتبر است.'
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorsArr = [];
            foreach ($errors as $error) {
                $obj = new \stdClass();
                $obj->error = $error;
                $errorsArr[] = $obj;
            }
            return response()->json(['errors' => $errorsArr], 422);
        }

        $user = auth()->guard('api')->user();
        $cart = new Cart();
        $cartQuery = $cart->dbSelect(Cart::FIELDS)
            ->where('user_id', '=', $user->id)
            ->get();
        if (count($cartQuery)== 0){
            return response()->json(['error' => 'not founded'] , 404);
        }
        $cartQuery = collect($cartQuery);
        foreach ($cartQuery as $item) {
            $product = Products::find($item->product_id);
            if ($product->quantity && $item->quantity > $product->quantity) {
                return response()->json(['error' => 'تعداد سفارش ' .$product->name. ' از حد مجاز بیشتر می باشد'] , 406);
            }
        }

        return response()->json(['status' => 'success'] , 200);
    }
}
