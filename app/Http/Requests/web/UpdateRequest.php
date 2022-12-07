<?php

namespace App\Http\Requests\web;

use App\Rules\StoreRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
//        return auth()->user()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'slogan'                      => 'required|string|max:30' ,
            'address'                     => 'required|string|exists:address,id' ,
            'guild'                       => 'nullable|exists:guild,id' ,
            'store_name'                  => 'required|string' ,
            'min_pay'                     => 'nullable|numeric' ,
            'about'                       => 'required|string' ,
            'telephone_number'            => 'required|numeric' ,
            'store_type'                  => 'required|in:service,product,market',
//            'payment_type'                => 'required|in:online,postal,both' ,
            'activity_type'               => 'required|in:country,province' ,
            // 'thumbnail_photo'             => 'nullable|image|mimes:jpeg,png,jpg|max:512' ,
            'visible'                     => 'nullable|in:on' ,
            'show_telephone_number'       => 'nullable|in:on' ,
        ];
    }

    public function messages()
    {
        return [
            'slogan.required'        => 'شعار فروشگاه الزامی است.',
            'slogan.max'             => 'حداکثر طول شعار فروشگاه 30 کاراکتر است.',
            'address.required'       => 'آدرس فروشگاه الزامی است.',
            'address.exists'         => 'آدرس فروشگاه نامعتبر است.',
//            'guild.required'         => 'صنف الزامی است.',
            'guild.exists'           => 'صنف نامعتبر است.',
            'store_name.required'    => 'نام فروشگاه الزامی است.',
            'min_pay.numeric'        => 'حداقل مبلغ خرید نامعتبر است.',
            'about.required'         => 'درباره فروشگاه نامعتبر است.',
            'telephone_number.required'  => 'شماره تماس الزامی است.',
            'telephone_number.numeric'   => 'شماره تماس نامعتبر است.',
//            'payment_type.required'      => 'نحوه پرداخت الزامی است.',
//            'payment_type.in'            => 'نحوه پرداخت نامعتبر است.',
            'activity_type.required' => 'محدوده فعالیت الزامی است.',
            'activity_type.in'       => 'محدوده فعالیت نامعتبر است.',
            // 'thumbnail_photo.image'  => 'عکس بندانگشتی نامعتبر است.',
            // 'thumbnail_photo.mimes'  => 'عکس بندانگشتی نامعتبر است.',
            // 'thumbnail_photo.max'    => 'حجم عکس بندانگشتی حداکثر 512 کیلیبایت است.',
        ];
    }

}
