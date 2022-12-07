<?php

namespace App\Http\Controllers;

use App\Category;
use App\City;
use App\SubCategory;
use App\Libraries\Swal;
use App\VipAds;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SubCategoryController extends Controller
{

    public function list(Category $category)
    {
        $guild = $category->guild;
        $sub_categories = DB::table('sub_category')
            ->where('category_id', '=', $category->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.subcategory.index', compact('category', 'sub_categories', 'guild'));
    }

    public function save(Request $request, Category $category)
    {
        $this->validate($request, [
            'name' => 'required|string|max:200',
            'photo' => 'nullable|file',
            'commission' => 'required|numeric|min:0|max:99',

        ], [
            'name.required' => 'نام دسته الزامی است.',
            'name.string' => 'نام دسته نامعتبر است.',
            'name.max' => 'نام زیر دسته طولانی تر از حد مجاز است.',
            'photo.file' => 'تصویر نامعتبر است.'
        ]);

        $subCategory = new SubCategory();
        $subCategory->name = $request->name;
        $subCategory->category_id = $category->id;
        $subCategory->commission = $request->commission;

        if ($request->hasFile('photo')) {
            $imgName = uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('icon'), $imgName);
            $subCategory->icon = $imgName;
        }
        $subCategory->save();
        Swal::success('ثبت موفقیت آمیز', 'زیر دسته مورد نظر با موفقیت ثبت شد.');
        return redirect()->back();
    }

    public function delete(SubCategory $subCategory)
    {
        try {
            $subCategory->delete();
        } catch (QueryException $exception) {
            if ($exception->errorInfo[1] == 1451) {
                $msg = new \stdClass();
                $msg->title = 'خطا';
                $msg->msg = 'امکان حذف زیر دسته به دلیل وجود رکوردهای مرتبط وجود ندارد.';
                return redirect()->back()->with('error_msg', $msg);
            }
        }
        Swal::success('حذف موفقیت آمیز', 'زیر دسته با موفقیت حذف شد.');
        return redirect()->back();

    }

    public function updateOrders(Request $request)
    {
        if ($request->has('id') && $request->has('ordering_factor')) {
            foreach ($request->id as $index => $value) {
                $cat = SubCategory::find($value);
                $cat->ordering_factor = $request->ordering_factor[$index];
                $cat->save();
            }
        }
        return response()->json(['status' => 204]);
    }

    public function update(SubCategory $subCategory, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:200',
            'image' => 'nullable|file',
            'commission' => 'required|numeric|min:0|max:99',
        ], [
            'name.required' => 'نام دسته الزامی است.',
            'name.string' => 'نام دسته نامعتبر است.',
            'name.max' => 'نام زیر دسته طولانی تر از حد مجاز است.',
            'image.file' => 'تصویر نامعتبر است.',
        ]);
        $subCategory->name = $request->name;
        $subCategory->commission = $request->commission;

        if ($request->hasFile('image')) {
            $imgName = uniqid() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('icon'), $imgName);
            $subCategory->icon = $imgName;
        }
        $subCategory->save();
        Swal::success('ویرایش موفقیت آمیز.', 'زیر دسته با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function getByCategory(Request $request, Category $category)
    {
        $subcategories = SubCategory::where('category_id', $category->id)->get();
        return response()->json($subcategories);
    }


}
