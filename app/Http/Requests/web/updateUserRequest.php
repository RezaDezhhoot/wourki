<?php

namespace App\Http\Requests\web;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class updateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'nullable|string|max:200',
            'last_name' => 'nullable|string|max:200',
        ];
    }

    public function messages()
    {
        return [
            'first_name.string' => 'نام نامعتبر است.',
            'first_name.max' => 'نام طولانی تر از حد مجاز است.',
            'last_name.string' => 'نام خانوادگی نامعتبر است.',
            'last_name.max' => 'نام خانوادگی طولانی تر از حد مجاز است.',
        ];
    }

    /*public function withValidator(Validator $validator)
    {
        $user = auth()->guard('web')->user();
        if ($user->email !== $this->request->email) {
            $validator->after(function ($validator) {
                $emailExisted = User::where('email', $this->request->email)->exists();
                if ($emailExisted) {
                    $validator->errors()->add('email', 'ایمیل وارد شده قبلا توسط کاربر');
                }
            });
        }
    }*/
}
