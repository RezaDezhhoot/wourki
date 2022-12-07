<?php

namespace App\Http\Requests\web;

use Illuminate\Foundation\Http\FormRequest;

class StoreService extends FormRequest
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
            'name'        => 'required|string',
            'description' => 'required|string',
            'price'       => 'required|numeric',
            'discount'    => 'nullable|numeric',
            'visible'     => 'nullable|in:on',
            'category'    => 'required|exists:category,id',
            'shipping_price_to_tehran' => $this->has('deliver_today_in_tehran') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'shipping_price_to_other_towns' =>$this->has('deliver_today_in_other_towns_check') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'deliver_day_in_tehran' => $this->has('delivery_in_tehran_without_price') ? 'nullable|numeric|min:0' : 'required|numeric|min:0',
            'deliver_day_in_other_towns' => $this->has('free_shipping_to_other_towns') ? 'nullable|numeric|min:0' : 'required|numeric|min:0'
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => 'نام محصول الزامی است',
            'description.required' => 'توضیحات محصول الزامی است',
            'price.required'       => 'قیمت محصول الزامی است',
            'price.numeric'        => 'قیمت محصول نامعتبر است',
            'discount.numeric'     => 'تخفیف نامعتبر الزامی است',
            'category.required'    => 'دسته بندی الزامی است',
            'category.exists'      => 'دسته بندی نامعتبر است',
            'shipping_price_to_tehran.required' => 'هزینه حمل به تهران را وارد نمایید.',
            'shipping_price_to_other_towns.required' => 'هزینه حمل به شهرستان ها را وارد نمایید.',
            'deliver_day_in_tehran.required' => 'زمان ارسال به تهران را وارد نمایید.',
            'deliver_day_in_other_towns.required' => 'زمان ارسال به شهرستان ها را وارد نمایید.',
            'shipping_price_to_tehran.numeric' => 'هزینه حمل به تهران نامعتبر است.',
            'shipping_price_to_other_towns.numeric' => 'هزینه حمل به شهرستان ها نامعتبر است.',
            'deliver_day_in_tehran.numeric' => 'زمان ارسال به تهران نامعتبر است.',
            'deliver_day_in_other_towns.numeric' => 'زمان ارسال به شهرستان ها نامعتبر است.',
        ];
    }
}
