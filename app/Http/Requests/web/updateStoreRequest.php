<?php

namespace App\Http\Requests\web;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class updateStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'slogan' => 'required|string|max:30',
            'min_pay' => 'required|numeric',
            'guild' => 'required|numeric|exists:guild,id',
            'address' => 'required|string',
            'about' => 'required|string',
            'phone_number' => 'required|numeric',
            'pay_type' => 'nullable|string|in:online,postal,both',
            'activity_type' => 'nullable|string|in:country,province',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'وارد کردن نام فروشگاه الزامی است.',
            'name.string' => 'نام فروشگاه نامعتبر است.',
            'name.max' => 'طول نام فروشگاه بیش از حد مجاز است.',
            'slogan.required' => 'وارد کردن شعار فروشگاه الزامی است.',
            'slogan.string' => 'شعار فروشگاه نامعتبر است.',
            'slogan.max' => 'طول شعار مجاز برای فروشگاه 30 کاراکتر است.',
            'min_pay.required' => 'وارد کردن حداقل قیمت الزامی است.',
            'min_pay.numeric' => 'حداقل قیمت نامعتبر است.',
            'guild.required' => 'وارد کردن صنف الزامی است.',
            'guild.numeric' => 'صنف نامعتبر است.',
            'guild.exists' => 'صنف نامعتبر است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'address.string' => 'آدرس نامعتبر است.',
            'about.required' => 'وارد کردن درباره ما الزامی است.',
            'about.string' => 'درباره ما نامعتبر است.',
            'phone_number.required' => 'وارد کردن شماره تماس الزامی است.',
            'phone_number.numeric' => 'شماره تماس نامعتبر است.',
            'pay_type.string' => 'نوع پرداختی نامعتبر است.',
            'pay_type.in' => 'نوع پرداختی نامعتبر است.',
            'activity_type.string' => 'نوع فعالیت نامعتبر است.',
            'activity_type.in' => 'نوع فعالیت نامعتبر است.',
        ];
    }

}
