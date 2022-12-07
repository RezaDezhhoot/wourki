<?php

namespace App\Http\Requests\web;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class filterProductSellerRequest extends FormRequest
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
            'store' => 'nullable|numeric|exists:store,id',
            'key_word' => 'nullable|string|max:100',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',
            'visibility' => 'nullable|numeric|in:1,0',
            'exists' => 'nullable|numeric|in:1,0',
            'status' => 'nullable|string|in:approved,rejected,pending',
        ];
    }

    public function messages()
    {
        return [
            'store.numeric' => 'فروشگاه نامعتبر است.',
            'store.exists' => 'فروشگاه نامعتبر است.',
            'key_word.string' => 'کلمه کلیدی نامعتبر است.',
            'key_word.max' => 'طول کلمه کلیدی بیش از حد مجاز است.',
            'price_from.numeric' => 'مبلغ ابتدایی نامعتبر است.',
            'price_to.numeric' => 'مبلغ انتهایی نامعتبر است.',
            'visibility.numeric' => 'وضعیت نمایش محصول نامعتبر است.',
            'visibility.in' => 'وضعیت نمایش محصول نامعتبر است.',
            'exists.numeric' => 'موجودی محصول نامعتبر است.',
            'exists.in' => 'موجودی محصول نامعتبر است.',
            'status.string' => 'وضعیت تایید محصول نامعتبر است.',
            'status.in' => 'وضعیت تایید محصول نامعتبر است.',
        ];
    }

}
