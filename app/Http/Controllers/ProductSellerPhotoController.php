<?php

namespace App\Http\Controllers;

use App\Libraries\Swal;
use App\Product_seller_photo;
use App\ProductSeller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductSellerPhotoController extends Controller
{
    public function index(ProductSeller $product)
    {
//        $photos = Product_seller_photo::where('seller_product_id' , $product)->get();
        $photos = $product->photos;
        return view('frontend.my-account.products.photos', compact('photos', 'product'));
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'file' => 'array|min:1',
            'file.*' => 'image|mimes:jpeg,jpg,png|max:1024',
            'product_id' => 'required|exists:product_seller,id',
            'product_id' => 'required|exists:product_seller,id',
        ], [
            'file.min' => 'انتخاب حداقل یک عکس الزامی است',
            'file.*.image' => 'عکس نامعتبر است.',
            'file.*.mimes' => 'عکس نامعتبر است.',
            'file.*.max' => 'حجم عکس حداکثر یک مگابایت است.',
        ]);
        $photos = $request->file;
        foreach ($photos as $index => $photo) {
            $imageName = uniqid() . '.' . $photo->getClientOriginalExtension();

            Image::make($photo)
                ->fit(1920, 1080, function ($constraint) {
                    $constraint->upsize();
                })
                ->save(public_path('/image/product_seller_photo/') . '/' . $imageName);
            Image::make($photo)
                ->fit(350, 350, function ($constraint) {
                    $constraint->upsize();
                })
                ->save(public_path('/image/product_seller_photo/350') . '/' . $imageName);

            $productPhoto = new Product_seller_photo();
            $productPhoto->seller_product_id = $request->product_id;
            $productPhoto->file_name = $imageName;
            $productPhoto->save();
        }
        $product = ProductSeller::find($request->product_id);
        $product->status = 'pending';
        $product->save();
        Swal::success('موفقیت آمیز.', 'عکس های مورد نظر با موفقیت شما آپلود شدند تصاویر بعد از بررسی توسط مدیر روی سایت منتشر می شوند.');
        return back();
    }

    public function delete(Product_seller_photo $photo)
    {
        $photo->delete();
//        unlink(public_path('/image/product_seller_photo/') . '/' . $photo->file_name);
//        unlink(public_path('/image/product_seller_photo/350') . '/' . $photo->file_name);
        Swal::success('حذف موفقیت آمیز محصول.', 'عکس مورد نظر با موفقیت شما حذف شد.');
        return back();
    }


}
