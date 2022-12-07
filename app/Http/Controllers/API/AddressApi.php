<?php

namespace App\Http\Controllers\API;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressApi extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();
        $address = new Address();
        $address->user_id = $user->id;
        $address->city_id = $request->city_id;
        $address->address = $request->address;
        $address->postal_code = $request->postal_code;
        $address->phone_number = $request->phone_number;
        $address->type = $request->type;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();
        return response()->json(['status' => 200] , 200);
    }

    public function update(Request $request)
    {
        $address = Address::where('id' , $request->id)->first();
        if ($request->filled('city_id'))
            $address->city_id = $request->city_id;
        if ($request->filled('address'))
            $address->address = $request->address;
        if ($request->filled('postal_code'))
            $address->postal_code = $request->postal_code;
        else
            $address->postal_code = null;
        if ($request->filled('phone_number'))
            $address->phone_number = $request->phone_number;
        if ($request->filled('type'))
            $address->type = $request->type;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();
        return response()->json(['status' => 200] , 200);
    }

    public function index(Request $request)
    {
        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : 1;
        $user = auth()->guard('api')->user();
        $address = Address::join('city' , 'city.id' , 'address.city_id')
            ->join('province' , 'province.id' , 'city.province_id')
            ->where('user_id' , $user->id)
            ->where('status', 'active')
            ->select('address.*' , 'city.name as city_name' , 'city.id as city_id' , 'province.name as province_name' , 'province.id as province_id')
            ->offset($offset)->limit($limit)->get();
        return response()->json($address , 200);
    }

    public function delete(Request $request)
    {
        $address = Address::find($request->id);
        $address->status = 'deleted';
        $address->save();
        return response()->json(['status' => 200] , 200);
    }
}
