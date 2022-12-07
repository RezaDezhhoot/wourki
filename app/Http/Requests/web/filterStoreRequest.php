<?php

namespace App\Http\Requests\web;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class filterStoreRequest extends FormRequest
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
            'province' => 'nullable|numeric|exists:province,id',
            'city' => 'nullable|numeric|exists:city,id',
            'guild' => 'nullable|numeric|exists:guild,id',
            'visibility' => 'nullable|numeric|in:1,0',
            'status' => 'nullable|string|in:approved,rejected,pending',
            'subscription' => 'nullable|numeric|in:1,0',
            'store_name' => 'nullable|string|max:255',
            'pay_type' => 'nullable|string|in:online,postal,both',
            'activity_type' => 'nullable|string|in:country,province',
        ];
    }

    public function messages()
    {
        return [
            'province.numeric' => 'انتخاب استان نامعتبر است.',
            'province.exists' => 'انتخاب استان نامعتبر است.',
            'city.numeric' => 'انتخاب شهر نامعتبر است.',
            'city.exists' => 'انتخاب شهر نامعتبر است.',
            'guild.numeric' => 'انتخاب صنف نامعتبر است.',
            'guild.exists' => 'انتخاب صنف نامعتبر است.',
            'visibility.numeric' => 'انتخاب وضعیت نمایش نامعتبر است.',
            'visibility.exists' => 'انتخاب وضعیت نمایش نامعتبر است.',
            'status.string' => 'انتخاب وضعیت تایید نامعتبر است.',
            'status.exists' => 'انتخاب وضعیت تایید نامعتبر است.',
            'subscription.numeric' => 'انتخاب وضعیت اشتراک نامعتبر است.',
            'subscription.exists' => 'انتخاب وضعیت اشتراک نامعتبر است.',
            'store_name.string' => 'نام فروشگاه نامعتبر است.',
            'store_name.max' => 'نام فروشگاه بیش از حد مجاز است است.',
            'pay_type.string' => 'نوع پرداختی نامعتبر است.',
            'pay_type.in' => 'نوع پرداختی نامعتبر است.',
            'activity_type.string' => 'نوع فعالیت نامعتبر است.',
            'activity_type.in' => 'نوع فعالیت نامعتبر است.',
        ];
    }

}
