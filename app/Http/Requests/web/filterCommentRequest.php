<?php

namespace App\Http\Requests\web;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class filterCommentRequest extends FormRequest
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
            'store_name' => 'nullable|numeric|exists:store,id',
            'product_name' => 'nullable|numeric|exists:product_seller,id',
            'status' => 'nullable|string|in:approved,rejected,pending',
            'start_date_ts' => 'nullable|numeric',
            'end_date_ts' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'store_name.numeric' => 'نام فروشگاه نامعتبر است',
            'store_name.exists' => 'نام فروشگاه نامعتبر است',
            'product_name.numeric' => 'نام محصول نامعتبر است',
            'product_name.exists' => 'نام محصول نامعتبر است',
            'status.string' => 'وضعیت تایید نظر نامعتبر است',
            'status.exists' => 'وضعیت تایید نظر نامعتبر است',
            'start_date_ts.numeric' => 'انتخاب تاریخ نامعتبر است.',
            'end_date_ts.numeric' => 'انتخاب تاریخ نامعتبر است.',
        ];
    }

}
