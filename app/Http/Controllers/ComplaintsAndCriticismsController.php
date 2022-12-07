<?php

namespace App\Http\Controllers;


use App\Libraries\Swal;
use App\Mail\ComplaintsAndCriticismsMail;
use Illuminate\Http\Request;
use Mail;

class ComplaintsAndCriticismsController extends Controller
{

    public function sendComplaintsAndCriticismsMail(Request $request)
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

        Mail::to(env('MAIL_USERNAME'))->send(new ComplaintsAndCriticismsMail($email , $message));
        Swal::success('موفقیت آمیز.', 'پیام شما با موفقیت ارسال شد.');
        return back();
    }

}
