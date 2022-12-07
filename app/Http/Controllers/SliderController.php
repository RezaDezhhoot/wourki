<?php

namespace App\Http\Controllers;

use App\Category;
use App\Libraries\Swal;
use App\ProductSeller;
use App\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function createSlider()
    {
        $products = ProductSeller::where('visible' , 1)
            ->where('status', 'approved')
            ->select('id' , 'name')
            ->get();
        return view('admin.slider.create' , compact('products'));
    }

    public function saveSlider(Request $request)
    {

        $this->validate($request , [
            'alt' => 'nullable|string|max:200',
            'photo' => 'required|image|max:5120',
            'slider_type' => 'required|in:'.Slider::HOME.','.Slider::STORE.','.Slider::PRODUCT.','.Slider::SERVICE,
            'link_to' => 'required|string|in:none,product,store',
            'product_id' => 'required_if:link_to,product|numeric|exists:product_seller,id',
            'store_id' => 'required_if:link_to,store|numeric|exists:store,id',
        ] , [
            'alt.max' => 'توضیحات عکس طولانی تر از حد مجاز است.',
            'photo.image' => 'عکس نامعتبر است.',
            'photo.required' => 'انتخاب عکس الزامی است.',
            'photo.max' => 'حجم تصویر حداکثر می تواند 5 مگابایت باشد.',
            'link_to.required' => 'انتخاب این فیلد الزامی است.',
            'link_to.string' => 'این فیلد نامعتبر است.',
            'link_to.in' => 'این فیلد تنها می تواند یکی از مقادیر محصول، فروشگاه یا هیچ کدام را داشته باشد.',
            'product_id.required_if' => 'انتخاب محصول الزامی است.',
            'product_id.numeric' => 'محصول انتخاب شده نامعتبر است.',
            'product_id.exists' => 'محصول انتخاب شده نامعتبر است.',
            'store_id.required_if' => 'انتخاب فروشگاه الزامی است.',
            'store_id.numeric' => 'فروشگاه انتخاب شده نامعتبر است.',
            'store_id.exists' => 'فروشگاه انتخاب شده نامعتبر است.',
        ]);
        $slider = new Slider();
        $slider->alt = $request->alt;
        $slider->type = $request->slider_type;
        if($request->hasFile('photo')){
            $imgName = uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('image/slider') , $imgName);
            $slider->pic = $imgName;
        }
        if($request->filled('link_to')){
            if($request->link_to == 'product'){
                $slider->product_id = $request->product_id;
            }else if($request->link_to == 'store'){
                $slider->store_id = $request->store_id;
            }
        }
        $slider->save();
        Swal::success('ثبت موفقیت آمیز' , 'اسلایدر با موفقیت ثبت شد.');
        return redirect()->back()->with('success_msg');
    }

    public function viewSlider()
    {
        $sliders = Slider::latest()->paginate(20);
        return view('admin.slider.view' , compact('sliders'));
    }

    public function deleteSlider(Slider $sliderId)
    {
        $sliderId->delete();
        Swal::success('حذف موفقیت آمیز' , 'اسلایدر مورد نظر با حذف شد.');
        return redirect()->back()->with('success_msg');
    }

    public function editSlider(Slider $sliderId)
    {
       $products = $sliderId->product;
       $store = $sliderId->store;
        return view('admin.slider.edit' , compact('sliderId' , 'products' , 'store'));
    }

    public function updateSlider(Request $request , Slider $sliderId)
    {
        $this->validate($request , [
            'alt' => 'nullable|string|max:200',
            'slider_type' => 'required|in:' . Slider::HOME . ',' . Slider::STORE . ',' . Slider::PRODUCT . ',' . Slider::SERVICE,
            'photo' => 'required|image|max:5120',
            'link_to' => 'required|string|in:none,product,store',
            'product_id' => 'required_if:link_to,product|numeric|exists:product_seller,id',
            'store_id' => 'required_if:link_to,store|numeric|exists:store,id',
        ] , [
            'alt.max' => 'توضیحات عکس طولانی تر از حد مجاز است.',
            'photo.image' => 'عکس نامعتبر است.',
            'photo.required' => 'انتخاب عکس الزامی است.',
            'photo.max' => 'حجم تصویر حداکثر می تواند 5 مگابایت باشد.',
            'link_to.required' => 'انتخاب این فیلد الزامی است.',
            'link_to.string' => 'این فیلد نامعتبر است.',
            'link_to.in' => 'این فیلد تنها می تواند یکی از مقادیر محصول، فروشگاه یا هیچ کدام را داشته باشد.',
            'product_id.required_if' => 'انتخاب محصول الزامی است.',
            'product_id.numeric' => 'محصول انتخاب شده نامعتبر است.',
            'product_id.exists' => 'محصول انتخاب شده نامعتبر است.',
            'store_id.required_if' => 'انتخاب فروشگاه الزامی است.',
            'store_id.numeric' => 'فروشگاه انتخاب شده نامعتبر است.',
            'store_id.exists' => 'فروشگاه انتخاب شده نامعتبر است.',
        ]);
        $sliderId->alt = $request->alt;
        $sliderId->type = $request->slider_type;
        if($request->hasFile('photo')){
            $imgName = uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('image/slider') , $imgName);
            $sliderId->pic = $imgName;
        }
        if($request->filled('link_to')){
            if($request->link_to == 'product'){
                $sliderId->product_id = $request->product_id;
                $sliderId->store_id = null;
            }else if($request->link_to == 'store'){
                $sliderId->store_id = $request->store_id;
                $sliderId->product_id = null;
            }else{
                $sliderId->store_id = null;
                $sliderId->product_id = null;
            }
        }
        $sliderId->save();
        Swal::success('موفقیت آمیز.' , 'اسلایدر مورد نظر با موفقیت به روز رسانی شد.');
        return redirect()->route('viewSlider')->with('success_msg');
    }

}
