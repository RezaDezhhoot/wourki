<?php

namespace App\Http\Requests\web;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class filterBillRequest extends FormRequest
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
            'province_buyer' => 'nullable|numeric|exists:province,id',
            'city_buyer' => 'nullable|numeric|exists:city,id',
            'province_seller' => 'nullable|numeric|exists:province,id',
            'city_seller' => 'nullable|numeric|exists:city,id',
            'guild' => 'nullable|numeric|exists:guild,id',
            'status' => 'nullable|in:delivered,rejected,pending,paid_back',
            'store' => 'nullable|numeric|exists:store,id',
            'product' => 'nullable|numeric|exists:product_seller,id',
            'price_from' => 'nullable|numeric',
            'price_to' => 'nullable|numeric',
            'start_date_ts' => 'nullable|numeric',
            'end_date_ts' => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'province_buyer.numeric' => 'انتخاب استان نامعتبر است.',
            'province_buyer.exists' => 'انتخاب استان نامعتبر است.',
            'city_buyer.numeric' => 'انتخاب شهر نامعتبر است.',
            'city_buyer.exists' => 'انتخاب شهر نامعتبر است.',
            'province_seller.numeric' => 'انتخاب استان نامعتبر است.',
            'province_seller.exists' => 'انتخاب استان نامعتبر است.',
            'city_seller.numeric' => 'انتخاب شهر نامعتبر است.',
            'city_seller.exists' => 'انتخاب شهر نامعتبر است.',
            'guild.numeric' => 'انتخاب صنف نامعتبر است.',
            'guild.exists' => 'انتخاب صنف نامعتبر است.',
            'status.in' => 'وضعیت فاکتور نامعتبر است.',
            'store.numeric' => 'فروشگاه نامعتبر است.',
            'store.exists' => 'فروشگاه نامعتبر است.',
            'product.numeric' => 'محصول نامعتبر است.',
            'product.exists' => 'محصول نامعتبر است.',
            'price_form.numeric' => 'قیمت ابتدایی نامعتبر است.',
            'price_to.numeric' => 'قیمت نهایی نامعتبر است.',
            'start_date_ts.numeric' => 'تاریخ ابتدایی نامعتبر است.',
            'end_date_ts.numeric' => 'تاریخ نهایی نامعتبر است.',
        ];
    }

}
