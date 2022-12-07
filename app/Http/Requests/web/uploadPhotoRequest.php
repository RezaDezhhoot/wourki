<?php

namespace App\Http\Requests\web;

use Illuminate\Foundation\Http\FormRequest;

class uploadPhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->guard('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'photo' => 'array|min:1',
            'photo.*' => 'nullable|image|mimes:jpeg,png,jpg|max:1024'
        ];
    }

    public function messages()
    {
        return [
            'photo.min' => 'عکسی جهت آپلود انتخاب نشده است.',
            'photo.*.mimes' => 'پسوند عکس نامعتبر است.',
            'photo.*.image' => 'پسوند عکس نامعتبر است.',
            'photo.*.max' => 'حداکثر حجم عکس 500 کیلوبایت است.',
        ];
    }
}
