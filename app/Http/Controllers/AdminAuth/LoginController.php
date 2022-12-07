<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Libraries\Swal;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller{
    use AuthenticatesUsers;

    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
//        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'login' => 'required|string|max:255',
            'password' => 'required|string'
        ] , [
            'login.required' => 'ایمیل یا شماره همراه الزامی است.',
            'password.required' => 'رمز عبور الزامی است.'
        ]);
        $remember = $request->has('remember') ? true : false;
        if (filter_var($request->login , FILTER_VALIDATE_EMAIL)){
            if(auth()->guard('admin')->attempt(['email' => $request->login , 'password' => $request->password] , $remember)){
                return redirect($this->redirectTo);
            }else{
                Swal::error('ناموفق !', 'ایمیل یا شماره موبایل اشتباه است.');
                return back();
            }
        }else{
            if(auth()->guard('admin')->attempt(['mobile' => $request->login , 'password' => $request->password] , $remember)){
                return redirect($this->redirectTo);
            }else{
                Swal::error('ناموفق !', 'ایمیل یا شماره موبایل اشتباه است.');
                return back();
            }
        }
    }

    public function adminLogout(){
        auth()->guard('admin')->logout();
        return redirect()->guest(route('admin.login'));
    }


}