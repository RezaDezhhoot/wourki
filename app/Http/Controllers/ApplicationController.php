<?php

namespace App\Http\Controllers;

use App\Helpers\ApplicationHelper;
use App\Libraries\Swal;
use App\Setting;
use Cache;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function managementPage(Request $request){
        $helper = new ApplicationHelper();
        $app_location = $helper->getFullPath();
        return view('admin.application.management' , compact('app_location'));
    }
    public function upload(Request $request){
        $request->validate([
            'file' => 'required|file',
            'app_version' => 'string|nullable'
        ]);
        $helper = new ApplicationHelper();
        $result = $helper->upload($request->file('file'));
        if($request->has('app_version')){
            Setting::first()->update([
                'app_version' => $request->app_version
            ]);
        }
        if($result){
            Swal::success('موفق','فایل با موفقیت ویرایش شد');
            return back();
        }
        else{
            Swal::error('خطا' , 'مشکلی در آپلود فایل به وجود آمده است');
            return back();
        }
    }
}
