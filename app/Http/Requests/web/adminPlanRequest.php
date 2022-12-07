<?php

namespace App\Http\Requests\web;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class adminPlanRequest extends FormRequest
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
            'name'        => 'required|string|max:255',
            'time'        => 'required|numeric',
            'price'       => 'required|numeric',
            'description' => 'required|string',
            'status'      => 'required|in:show,hide',
            'type'        => 'required|in:store,market'
        ];
    }

    public function messages()
    {
        return [
            'name.required'        => 'نام پلن الزامی است.',
            'name.string'          => 'نام پلن نامعتبر است.',
            'name.max'             => 'نام پلن بیش ار حد مجاز است.',
            'time.required'        => 'زمان پلن الزامی است.',
            'time.numeric'         => 'زمان پلن نامعتبر است.',
            'price.required'       => 'قیمت پلن الزامی است.',
            'price.numeric'        => 'قیمت پلن نامعتبر است.',
            'description.required' => 'توضیحات پلن الزامی است.',
            'description.string'   => 'توضیحات پلن نامعتبر است.',
            'status.required'      => 'وضعیت پلن الزامی است.',
            'status.in'            => 'وضعیت پلن نامعتبر است.',
            'type.required'            => 'نوع پلن الزامی است.',
            'type.in'            => 'نوع پلن نامعتبر است.',

        ];
    }



    /*public function withValidator(Validator $validator)
    {
        $user = auth()->guard('web')->user();
        if ($user->email !== $this->request->email) {
            $validator->after(function ($validator) {
                $emailExisted = User::where('email', $this->request->email)->exists();
                if ($emailExisted) {
                    $validator->errors()->add('email', 'ایمیل وارد شده قبلا توسط کاربر');
                }
            });
        }
    }*/
}
