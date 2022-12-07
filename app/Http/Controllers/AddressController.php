<?php

namespace App\Http\Controllers;

use App\Address;
use App\City;
use App\Http\Requests\web\AddressRequest;
use App\Libraries\Swal;
use App\Province;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function create(AddressRequest $request)
    {
        Address::create([
            'user_id' => auth()->guard('web')->user()->id ,
            'city_id' => $request->city ,
            'address' => $request->address ,
            'postal_code' => $request->postal_code ,
            'phone_number' => $request->phone_number ,
            'type' => $request->type ,
            'latitude' => $request->latitude ,
            'longitude' => $request->longitude ,
        ]);
        Swal::success('ثبت موفقیت آمیز.', 'آدرس جدید با موفقیت ثبت شد.');
        return redirect()->back();
    }

    public function createByAjax(Request $request)
    {
        Address::create([
            'user_id' => auth()->guard('web')->user()->id ,
            'city_id' => $request->city_id ,
            'address' => $request->address ,
            'postal_code' => $request->postal_code ,
            'phone_number' => $request->phone_number ,
            'type' => $request->type ,
            'latitude' => $request->latitude ,
            'longitude' => $request->longitude ,
        ]);
        return response()->json(200);
    }

    public function delete(Address $address)
    {
        try {
            $address->delete();
            Swal::success('حذف موفقیت آمیز.', 'آدرس مورد نظر با موفقیت حذف شد.');
        } catch (\Exception $e) {
            if ($e->getCode() == 23000)
                Swal::error('ناموفق!', 'آدرس مورد نظر برای فروشگاه شما انتخاب شده است و امکان حذف آن امکانپذیر نیست!');
        }

        return back();
    }

    public function edit(Address $address)
    {
        $provinces = Province::where('deleted' , 0)->get();
        $cities = City::where('deleted' , 0)
            ->where('province_id', $address->city->province_id)
            ->get();
        return view('frontend.my-account.addresses.edit' , compact('address' , 'provinces' , 'cities'));
    }

    public function update($address , Request $request)
    {
        $address = Address::find($address);
        $address->city->update([
           'province_id' => $request->province ,
        ]);
        $address->update([
            'city_id' => $request->city ,
            'address' => $request->address ,
            'postal_code' => $request->postal_code ,
            'phone_number' => $request->phone_number ,
            'type' => $request->type ,
            'latitude' => $request->latitude ,
            'longitude' => $request->longitude ,
        ]);
        Swal::success('ویرایش موفقیت آمیز.', 'آدرس مورد نظر با موفقیت ویرایش شد.');
        return redirect()->route('user.address');
    }

    public function userAddress()
    {
        $user = auth()->guard('web')->user();
        $provinces = Province::where('deleted' , 0)->get();
        $addresses = Address::where('user_id' , $user->id)
            ->where('status' , 'active')
            ->get();
        $addresses->each(function ($address){
            $address->cityName = $address->city->name;
            $address->provinceName = $address->city->province->name;
        });
        return view('frontend.my-account.addresses.index' , compact('addresses' , 'provinces'));
    }

    public function getUserAddressByAjax()
    {
        $user = auth()->guard('web')->user();
        $address = Address::where('user_id' , $user->id)->latest()->first();
        return response()->json($address , 200);
    }

    public function adminAddressSave(Request $request){
        $this->validate($request , [
            'user_id' => 'required|numeric|exists:users,id',
            'city' => 'required|numeric|exists:city,id',
            'address' => 'required|string',
            'postal_code' => 'nullable|numeric',
            'telephone' => 'required|string',
            'place_type' => 'required|string|in:home,store,warehouse',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
        $address = new Address();
        $address->user_id = $request->user_id;
        $address->city_id = $request->city;
        $address->address = $request->address;
        $address->postal_code = $request->postal_code;
        $address->phone_number = $request->telephone;
        $address->type = $request->place_type;
        $address->status = 'active';
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->save();
        return response()->json([
            'status' => 200,
            'address' => $address
        ]);
    }
}
