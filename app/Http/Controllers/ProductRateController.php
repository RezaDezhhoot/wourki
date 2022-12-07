<?php

namespace App\Http\Controllers;

use App\ProductRate;
use App\ProductSeller;
use App\User;
use Illuminate\Http\Request;

class ProductRateController extends Controller
{
    public function store(Request $request ,  ProductSeller $product){
        $this->validate($request , [
            'rate' => 'required|numeric|min:1|max:5'
        ]);
        $user = auth()->guard('web')->user();
        ProductRate::updateOrCreate([
            'user_id' => $user->id,
            'product_seller_id' => $product->id,
        ] , [
            'rate' => $request->rate
        ]);

    }
}
