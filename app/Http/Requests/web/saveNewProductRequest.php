<?php

namespace App\Http\Requests\web;

use App\Libraries\RetailerTabs;
use App\Libraries\Swal;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class saveNewProductRequest extends FormRequest
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
            'name' => 'required|string|max:300',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|numeric|min:1',
            'subCategory' => 'required|exists:sub_category,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'نام الزامی است.',
            'name.string' => 'نام نامعتبر است.',
            'name.max' => 'نام طولانی تر از حد مجاز است.',
            'description.required' => 'توضیحات محصول الزامی است.',
            'description.string' => 'توضیحات محصول نامعتبر است.',
            'price.required' => 'قیمت الزامی است.',
            'price.numeric' => 'قیمت نامعتبر است.',
            'price.min' => 'قیمت کمتر از حد مجاز است.',
            'discount.required' => 'درصد تخفیف الزامی است.',
            'discount.numeric' => 'درصد تخفیف باید عددی بین 0 تا 100 باشد.',
            'discount.min' => 'درصد تخفیف باید عددی بین 0 تا 100 باشد.',
            'discount.max' => 'درصد تخفیف باید عددی بین 0 تا 100 باشد.',
            'quantity.required' => 'تعداد محصول الزامی است.',
            'quantity.numeric' => 'موجودی محصول باید عددی باشد.',
            'quantity.min' => 'موجودی محصول کمتر از حد مجاز است.',
            'subCategory.required' => 'انتخاب دسته بندی الزامی است.',
            'subCategory.exists' => 'دسته بندی نامعتبر است.'

        ];
    }
    public function formatErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

    public function response(array $errors)
    {
        Swal::error('خطا' , implode('\r\n' , $errors));
    }
}
