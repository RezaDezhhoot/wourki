<?php

namespace App\Http\Requests\web;

use App\Libraries\RetailerTabs;
use App\Libraries\Swal;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class registerUserInAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:200',
            'last_name' => 'required|string|max:200',
            'mobile' => 'required|numeric|digits:11|unique:users,mobile',
            'password' => 'required|string|min:6',
            'email' => 'nullable|email|unique:users,email',
//            'gcm_code' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'وارد کردن نام الزامی است.',
            'first_name.string' => 'نام نامعتبر است.',
            'first_name.max' => 'نام طولانی تر از حد مجاز است.',
            'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
            'last_name.max' => 'نام خانوادگی طولانی تر از حد مجاز است.',
            'mobile.required' => 'وارد کردن تلفن همراه الزامی است.',
            'mobile.numeric' => 'تلفن همراه نامعتبر است.',
            'mobile.digits' => 'تلفن همراه 11 عدد میباشد.',
            'mobile.unique' => 'تلفن همراه وارد شده از قبل ثبت شده است.',
            'password.required' => 'رمز عبور الزامی است.',
            'password.string' => 'رمز عبور نامعتبر است.',
            'password.min' => 'رمز عبور باید حداقل 6 کاراکتر باشد.',
            'email.email' => 'ایمیل معتبر نیست.',
            'email.unique' => 'ایمیل وارد شده از قبل ثبت شده است.',
//            'gcm_code.required' => 'کد gcm الزامی است.'

        ];
    }
    public function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

    public function response(array $errors)
    {
        Swal::error('خطا' , implode('\r\n' , $errors));
    }
}
