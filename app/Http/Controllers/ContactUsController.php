<?php

namespace App\Http\Controllers;


use App\AdsPosition;
use App\Libraries\Swal;
use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Mail;

class ContactUsController extends Controller
{

    public function sendContactUsMail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'message' => 'required|string',
        ] , [
            'email.required' => 'ایمیل الزامی است.',
            'email.string' => 'ایمیل نامعتبر است.',
            'email.email' => 'ایمیل نامعتبر است.',
            'message.required' => 'متن ایمیل الزامی است.',
            'message.string' => 'متن ایمیل نامعتبر است.',
        ]);
        $email = $request->email;
        $message = $request->message;

        Mail::to(env('MAIL_USERNAME'))->send(new ContactUsMail($email , $message));
        Swal::success('موفقیت آمیز.', 'پیام شما با موفقیت ارسال شد.');
        return back();
    }

    public function guidance(){
        $positions = AdsPosition::all();
        return view('frontend.guid.index' , compact('positions'));
    }

}
