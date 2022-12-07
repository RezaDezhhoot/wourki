<?php

namespace App\Http\Controllers;

use App\City;
use App\Libraries\Swal;
use App\Province;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function showByProvinceId(Request $request , $provinceId){
        $cities = DB::table('city')->where('province_id' , '=' , $provinceId)->orderBy('name' , 'asc')->get();
        return response()->json(['status' => 200 , 'list' => $cities]);
    }

    public function showAllInAdminPanel(Request $request , Province $province){
        $cities = DB::table('city')->where('province_id' , $province->id)->orderBy('name' , 'asc')->paginate(15);
        $data['cities'] = $cities;
        $data['province'] = $province;
        return view('admin.city.index')->with($data);
    }

    public function save(Request $request , Province $province){
        $this->validate($request , [
            'name' => 'required|string|max:200'
        ] , [
            'name.required' => 'نام شهر الزامی است.',
            'name.max' => 'نام شهر طولانی تر از حد مجاز است.'
        ]);

        $city = new City();
        $city->province_id = $province->id;
        $city->name = $request->name;
        $city->save();

        Swal::success('ثبت موفقیت آمیز' , 'شهر مورد نظر با موفقیت ثبت شد.');
        return redirect()->back()->with('success_msg');
    }

    public function update(Request $request , City $city){
        $this->validate($request , [
            'name' => 'required|string|max:200'
        ] , [
            'name.required' => 'نام شهر الزامی است.',
            'name.max' => 'نام شهر طولانی تر از حد مجاز است.'
        ]);

        $city->name = $request->name;
        $city->save();

        Swal::success('به روز رسانی موفقیت آمیز' , 'شهر مورد نظر با موفقیت به روز رسانی شد.');
        return redirect()->back()->with('success_msg');
    }

    public function delete(Request $request , City $city){
        try{
            $city->delete();
        }catch (QueryException $exception){
            if($exception->errorInfo[1] == 1451){
                $msg = new \stdClass();
                $msg->title = 'خطا';
                $msg->msg = 'امکان حذف شهر به دلیل وجود رکوردهای مرتبط وجود ندارد.';
                return redirect()->back()->with('error_msg' , $msg);
            }
        }
        Swal::success('حذف موفقیت آمیز' , 'شهر مورد نظر با حذف شد.');
        return redirect()->back()->with('success_msg');
    }

    public function getCityByAjax(Request $request)
    {
        $cities = City::where('province_id' , $request->province)
            ->where('deleted' , 0)
            ->get();
        return response()->json($cities , 200);
    }
}
