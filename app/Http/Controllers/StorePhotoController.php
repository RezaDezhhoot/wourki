<?php

namespace App\Http\Controllers;

use App\Libraries\Swal;
use App\Store;
use App\Store_photo;
use File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class StorePhotoController extends Controller
{
    public function index(Store $store)
    {
        $photo = $store->photo;
        return view('frontend.my-account.store.photo.create' , compact('photo' , 'store'));
    }
    public function delete(Store_photo $store_photo)
    {
        $store_photo->delete();
        Swal::success('حذف موفقیت آمیز.', 'عکس مورد نظر با موفقیت حذف شد.');
        return back();
    }

    public function uploadPhoto(Request $request)
    {
        $request->validate([
           'photo' => 'required|image|mimes:jpeg,png,jpg|max:1024' ,
        ] , [
            'photo.required' => 'انتخاب عکس الزامی است.',
            'photo.image' => 'عکس نامعتبر است.',
            'photo.mimes' => 'عکس نامعتبر است.',
            'photo.max' => 'حدکثر حجم عکس یک مگابایت است.',
        ]);
            $photoExists = Store_photo::where('store_id' , $request->store_id)->get();
            //deleting existing photos
            foreach ($photoExists as $photo) {
                $path = public_path('/image/store_photos/') . '/' . $photo->photo_name;
                if(File::exists($path)){
                    File::delete($path);
                }
            }
            Store_photo::where('store_id', $request->store_id)->delete();
            $image = $request->photo;
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            Image::make($image)
                ->fit(500 , 282 , function ($constraint) {
                    $constraint->upsize();
                })
                ->save(public_path('/image/store_photos/') .'/'. $imageName);
            $storePhoto = new Store_photo();
            $storePhoto->store_id = $request->store_id;
            $storePhoto->photo_name = $imageName;
            $storePhoto->save();
            Swal::success('آپلود موفقیت آمیز.', 'عکس مورد نظر با موفقیت آپلود شد.');
            return back();


    }
}
