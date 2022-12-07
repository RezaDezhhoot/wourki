<?php

namespace App\Http\Controllers;

use App\Categories;
use App\CategoriesOfGuild;
use App\Category;
use App\Http\Requests\saveNewProductRequest;
use App\Libraries\RetailerTabs;
use App\Libraries\Swal;
use App\ProductPhoto;
use App\Products;
use Cviebrock\EloquentSluggable\Tests\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductsPhotoController extends Controller
{
    public function showCreatePage(Request $request , Products $product)
    {
        $data['products'] = $product;
        $product_photo = $product->photo;
        $data['product_photos'] =$product_photo;
        return view('admin.product.adding-photo-product')->with($data);
    }

    public function save(Request $request , Products $product)
    {
        $this->validate($request , [
            'photo' => 'required|file|max:1024'
        ] , [
            'photo.file' => 'عکس نامعتبر است.',
            'photo.required' => 'انتخاب عکس الزامی است.',
            'photo.max' => 'حجم تصویر حداکثر می تواند 1 مگابایت باشد.'
        ]);

//        dd($request->all());
        if ($request->hasFile('photo')) {
            $photo = $request->photo;
            $imgName = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->move(public_path('image/product_photos'), $imgName);
//            Image::make(public_path('/image/product_photos/' . $imgName))->crop($request->width , $request->height , $request->x , $request->y)->save();
            $productPhoto = new ProductPhoto();
            $productPhoto->name = $imgName;
            $productPhoto->product_id = $product->id;
            $productPhoto->save();
            Swal::success('ثبت موفقیت آمیز عکس' , 'عکس شما با موفقیت ثبت شد.');
            return redirect()->back();
        }
    }

    public function deletePhoto(ProductPhoto $product)
    {
        $product->delete();
        Swal::success('حذف موفقیت آمیز عکس' , 'عکس با موفقیت حذف شد.');
        return redirect()->back();
    }

    public function showEditPage(Products $product)
    {
        $product_photos = $product->photo;
        $data['product_photos'] = $product_photos;
        $data['product'] = $product;
        return view('admin.product.edit-photo-product')->with($data);
    }
}
