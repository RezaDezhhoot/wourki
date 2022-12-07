<?php

namespace App\Http\Controllers;

use App\Guild;
use App\Libraries\Swal;
use Illuminate\Http\Request;

class GuildController extends Controller
{
    public function list()
    {
        $guilds = Guild::orderBy('id' , 'desc')->where('guild_type' , 'product')->paginate(20);
        return view('admin.guild.index' , compact('guilds'));
    }
    public function serviceList(){
        $guilds = Guild::orderBy('id' , 'desc')->where('guild_type' , 'service')->paginate(20);
        return view('admin.guild.service' , compact('guilds'));
    }
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'nullable|file',
            'guild_type' => 'required|in:service,product'
        ] , [
            'name.required' => 'نام صنف الزامی است.',
            'name.string' => 'نام صنف نامعتبر است.',
            'name.max' => 'طول نام صنف بیش از حد مجاز است.',
            'photo.file' => 'تصویر نامعتبر است.',
        ]);
        $guild = new Guild();
        $guild->name = $request->name;
        $guild->guild_type = $request->guild_type;
        if($request->hasFile('photo')){
            $imgName = uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('icon') , $imgName);
            $guild->pic = $imgName;
        }
        $guild->save();
        Swal::success('ساخت موفقیت آمیز.', 'صنف مورد نظر با موفقیت ایجاد شد.');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'photo' => 'nullable|file'
        ] , [
            'name.required' => 'نام صنف الزامی است.',
            'name.string' => 'نام صنف نامعتبر است.',
            'name.max' => 'طول نام صنف بیش از حد مجاز است.',
            'photo.file' => 'تصویر نامعتبر است.',
        ]);

        $guild = Guild::find($request->id);
        $guild->name = $request->name;
        if($request->hasFile('photo')){
            $imgName = uniqid() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('icon') , $imgName);
            $guild->pic = $imgName;
        }
        $guild->save();
        Swal::success('ویرایش موفقیت آمیز.', 'صنف مورد نظر با موفقیت ویرایش شد.');
        return redirect()->back();
    }

    public function delete(Guild $guild)
    {
        $guild->delete();
        Swal::success('حدف موفقیت آمیز.', 'صنف مورد نظر با موفقیت حذف شد.');
        return redirect()->back();
    }

    public function categoryGuild(Guild $guild)
    {
        $categories = $guild->categories;
        dd($categories);
    }
}
