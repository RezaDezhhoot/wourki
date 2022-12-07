<?php

namespace App\Http\Controllers;

use App\Category;
use App\Guild;
use App\Libraries\Swal;
use App\SubCategory;
use App\VipAds;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class CategoryController extends Controller
{

    public function list(Guild $guild){
        $categories = Category::where('guild_id' , $guild->id)
            ->orderBy('id' , 'asc')
            ->get();
        return view('admin.category.index' , compact('categories' , 'guild'));
    }

    public function store(Request $request){
        $this->validate($request , [
            'name' => 'required|string|max:100',
            'guild' => 'required|numeric|exists:guild,id',
            'photo' => 'nullable|file',
            'commission' => 'required|numeric|min:0|max:99',
        ] , [
            'name.required' => 'نام دسته الزامی است.',
            'name.string' => 'نام دسته نامعتبر است.',
            'name.max' => ' طول نام دسته بیش از حد مجاز است.',
            'photo.file' => 'تصویر نامعتبر است.',
        ]);
        $category = new Category();
        $category->name = $request->name;
        $category->guild_id = $request->guild;
        $category->commission = $request->commission;
        if($request->hasFile('photo')){
            $imgName = uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('icon') , $imgName);
            $category->icon = $imgName;
        }
        $category->save();
        Swal::success('ثبت موفقیت آمیز' , 'دسته با موفقیت ثبت شد.');
        return redirect()->back();
    }

    public function delete(Category $category){
        $subCats = $category->subCategories;
        $subCats->each(function ($subCat){
            $subCat->delete();
        });
        $category->delete();
        Swal::success('حذف موفقیت آمیز.' , 'دسته مورد نظر و زیر دسته های مربوطه حذف شد.');
        return redirect()->back();
    }

    public function update(Request $request , Category $category){
        $this->validate($request , [
            'name' => 'required|string|max:100',
            'photo' => 'nullable|file',
            'commission' => 'required|numeric|min:0|max:99',
        ] , [
            'name.required' => 'نام دسته الزامی است.',
            'name.string' => 'نام دسته نامعتبر است.',
            'name.max' => ' طول نام دسته بیش از حد مجاز است.',
            'photo.file' => 'تصویر نامعتبر است.',
        ]);
        $category->name = $request->name;
        $category->commission = $request->commission;
        if($request->hasFile('photo')){
            $imgName = uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('icon') , $imgName);
            $category->icon = $imgName;
        }
        $category->save();
        Swal::success('ویرایش موفقیت آمیز' , 'دسته با موفقیت ویرایش شد.');
        return redirect()->back()->with('success_msg');
    }

    public function getCategoryByAjax(Request $request)
    {
        $categories = Category::where('guild_id' , $request->guild)->get();
        return response()->json($categories , 200);
    }
}
