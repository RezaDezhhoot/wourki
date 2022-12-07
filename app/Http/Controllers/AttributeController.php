<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Libraries\Swal;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function list()
    {
        $attributes = Attribute::orderBy('id', 'desc')->paginate(20);
        return view('admin.attribute.index', compact('attributes'));
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'store_type' => 'required|in:service,product'
        ], [
            'name.required' => 'نام ویژگی الزامی است.',
            'name.string' => 'نام ویژگی نامعتبر است.',
            'name.max' => 'طول نام ویژگی بیش از حد مجاز است.',
        ]);
        Attribute::create([
            'type' => $request->name,
            'store_type' => $request->store_type
        ]);
        Swal::success('ساخت موفقیت آمیز.', 'ویژگی مورد نظر با موفقیت ایجاد شد.');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'store_type' => 'required|in:service,product'
        ], [
            'name.required' => 'نام ویژگی الزامی است.',
            'name.string' => 'نام ویژگی نامعتبر است.',
            'name.max' => 'طول نام ویژگی بیش از حد مجاز است.',
        ]);

        $attribute = Attribute::find($request->id);
        if(!$attribute){
            Swal::error('خطا در ویرایش', 'ویژگی مورد نظر یافت نشد.');
            return redirect()->back();
        }
        $attribute->type = $request->name;
        $attribute->store_type = $request->store_type;
        $attribute->save();
        Swal::success('ویرایش موفقیت آمیز.', 'ویژگی مورد نظر با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function delete(Attribute $attribute)
    {
        $attribute->delete();
        Swal::success('حدف موفقیت آمیز.', 'ویژگی مورد نظر با موفقیت حذف شد.');
        return redirect()->back();
    }
}
