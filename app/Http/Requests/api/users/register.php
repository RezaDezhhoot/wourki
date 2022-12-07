<?php

namespace App\Http\Requests\api\users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class register extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'mobile' => 'required|string|max:200|unique:users,mobile',
            'password' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'gcm_code' => 'required'
        ];
    }

    /**
     * @return array
     */
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
            'mobile.string' => 'تلفن همراه نامعتبر است.',
            'mobile.max' => 'تلفن همراه طولانی تر از حد مجاز است.',
            'mobile.unique' => 'تلفن همراه وارد شده از قبل ثبت شده است.',
            'password.required' => 'رمز عبور الزامی است.',
            'password.string' => 'رمز عبور نامعتبر است.',
            'email.email' => 'ایمیل معتبر نیست.',
            'gcm_code.required' => 'کد gcm الزامی است.'
        ];
    }

    protected function formatErrors(Validator $validator){
        $errors = $validator->errors()->all();
        $errorsArr = [];
        foreach($errors as $error){
            $obj = new \stdClass();
            $obj->error = $error;
            $errorsArr[] = $obj;
        }
        return $errorsArr;
    }

    public function response(array  $errors){
        return response()->json(['error' => $errors] , 400);
    }
}
