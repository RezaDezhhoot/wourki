<?php

namespace App\Http\Controllers;

use App\Exports\MarketerExport;
use App\Marketer;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class MarketerController extends Controller
{
    public function index(Request $request)
    {
        $this->validate($request , [
            'name' => 'nullable|string|max:255',
            'mobile' => 'nullable',
            'email' => 'nullable|email'
        ] , [
            'name.string' => 'نام و نام خانوادگی نامعتبر است.',
            'name.max' => 'نام طولانی تر از حد مجاز است.',
            'email.email' => 'ایمیل نامعتبر است.'
        ]);
        $users = Marketer::join('users' , 'users.id' , '=' , 'marketer.user_id')
            ->leftJoin('store' , 'store.user_id' , '=' , 'users.id')
            ->leftJoin('address' , 'address.id' , '=' , 'store.address_id')
            ->leftJoin('city' , 'city.id' , '=' , 'address.city_id')
            ->leftJoin('province' , 'province.id' , '=' , 'city.province_id')
            ->select('users.id' , 'users.first_name' , 'users.last_name' , 'users.mobile' , 'users.email' , 'users.created_at' , 'users.banned' , 'users.shaba_code' , 'marketer.created_at as marketer_created_at' ,
                'city.name as city_name' , 'province.name as province_name')
            ->groupBy('users.id' , 'users.first_name' , 'users.last_name' , 'users.mobile' , 'users.email' , 'users.created_at' , 'users.banned' , 'users.shaba_code' , 'marketer.created_at' ,
                'city.name' , 'province.name')
            ->addSelect(\DB::raw('(
                select sum(reagent_code.reagented_user_fee)
                from reagent_code
                where reagent_code.reagent_code = users.mobile and 
                reagent_code.checkout = 0 
            ) as total_credit'));
        if($request->filled('name')){
            $users->whereRaw('( MATCH(users.first_name , users.last_name) AGAINST("' . $request->name . '" IN NATURAL LANGUAGE MODE) )')
                ->addSelect(\DB::raw('(
                    MATCH(users.first_name , users.last_name) AGAINST("' . $request->name . '" IN NATURAL LANGUAGE MODE)
                ) as relevance'))
                ->orderBy('relevance' , 'desc');
        }else{
            $users->orderBy('marketer.created_at' , 'desc');
        }
        if($request->filled('mobile')){
            $users->where('users.mobile' , 'like' , "%". $request->mobile ."%");
        }
        if($request->filled('email')){
            $users->where('users.email' , '=' , $request->email);
        }
        $users = $users->paginate(15);
        return view('admin.users.list-of-marketer-users' , compact('users'));
    }

    public function attachUser(Request $request)
    {
        $user = User::find($request->user);
        Marketer::create([
           'user_id' => $user->id
        ]);
        return back();
    }

    public function detachUser(Request $request)
    {
        $marketer = Marketer::where('user_id' , $request->user)->first();
        User::where('id', $marketer->user_id)->update(['become_marketer' => 0]);
        $marketer->delete();
        return back();
    }

    public function marketerExportExcel()
    {
        return Excel::download(new MarketerExport(), 'marketers.xlsx');
    }

    public function storeShabaCode(User $user, Request $request)
    {
        $request->validate([
           'shaba_code' => 'required|string'
        ]);
        $user->shaba_code = $request->shaba_code;
        $user->save();
        return back();
    }

}
