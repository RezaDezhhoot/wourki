<?php

namespace App\Http\Requests\web;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
           'province' => 'required|exists:province,id' ,
           'city' => 'required|exists:city,id' ,
           'address' => 'required|string' ,
           'postal_code' => 'nullable|numeric' ,
           'phone_number' => 'required|numeric' ,
           'type' => 'required|in:home,store,warehouse' ,
        ];
    }

    public function messages()
    {
        return [
            'province.required' => 'انتخاب استان الزامی است.' ,
            'province.exists' => 'انتخاب استان نامعتبر است.' ,
            'city.required' => 'انتخاب شهر الزامی است.' ,
            'city.exists' => 'انتخاب شهر نامعتبر است.' ,
            'address.required' => 'فیلد آدرس الزامی است.' ,
            'address.string' => 'فیلد آدرس نامعتبر است.' ,
            'postal_code.string' => 'فیلد کدپستی نامعتبر است.' ,
            'phone_number.required' => 'فیلد تلفن تماس الزامی است.' ,
            'phone_number.string' => 'فیلد تلفن تماس نامعتبر است.' ,
            'type.required' => 'انتخاب نوع محل الزامی است.' ,
            'type.in' => 'انتخاب نوع محل نامعتبر است.' ,
        ];
    }

}
