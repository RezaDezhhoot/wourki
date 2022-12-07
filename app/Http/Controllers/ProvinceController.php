<?php

namespace App\Http\Controllers;

use App\City;
use App\Libraries\Swal;
use App\Province;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProvinceController extends Controller
{
    public function showAll(Request $request)
    {
        $provinces = DB::table('province')->orderBy('name' , 'asc')->get();
        return response()->json(['status' => 200, 'list' => $provinces]);
    }

    public function showAllInAdminPanel(Request $request)
    {
        $provinces = DB::table('province')->orderBy('name' , 'asc')->paginate(15);
        $data['provinces'] = $provinces;
        return view('admin.province.index')->with($data);
    }

    public function update(Request $request, Province $province)
    {
        $this->validate($request , [
            'name' => 'required|string|max:100'
        ], [
            'name.required' => 'نام استان الزامی است.',
            'name.max' => 'نام استان طولانی تر از حد مجاز است.'
        ]);

        $province->name = $request->name;
        $province->save();

        Swal::success('به روز رسانی موفقیت آمیز' , 'استان مورد نظر با موفقیت به روز رسانی شد.');
        return redirect()->back()->with('success_msg');
    }

    public function delete(Request $request, Province $province)
    {
        try {
            $province->delete();
        } catch (QueryException $exception) {
            if ($exception->errorInfo[1] == 1451) {
                $msg = new \stdClass();
                $msg->title = 'خطا';
                $msg->msg = 'امکان حذف استان به دلیل وجود رکوردهای مرتبط وجود ندارد. ';
                return redirect()->back()->with('error_msg', $msg);
            }
        }
        Swal::success('حذف موفقیت آمیز' , 'استان مورد نظر با حذف شد.');
        return redirect()->back()->with('success_msg');
    }

    public function save(Request $request){
        $this->validate($request , [
            'name' => 'required|string|max:100'
        ], [
            'name.required' => 'نام استان الزامی است.',
            'name.max' => 'نام استان طولانی تر از حد مجاز است.'
        ]);

        $province = new Province();
        $province->name = $request->name;
        $province->save();

        Swal::success('ثبت موفقیت آمیز' , 'استان مورد نظر با موفقیت ثبت شد.');
        return redirect()->back()->with('success_msg');
    }

    public function getByCity(Request $request , Province $province)
    {
        $city = City::where('province_id' , $province->id)->get();
        return response()->json($city);
    }

    public function setCookie(Province $province)
    {
        Cookie::queue('province' , $province->id);
        return redirect()->route('mainPage');
    }
}
