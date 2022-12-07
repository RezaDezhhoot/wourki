<?php

namespace App\Rules;

use App\Store;
use Illuminate\Contracts\Validation\Rule;

class StoreRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = auth()->guard('web')->user();
        $storeUsername = Store::where('user_id' , $user->id)->first()->user_name;
        $checkUsername = Store::where('user_name' , $value)->count();
        if ($storeUsername != null && $storeUsername == $value){
            return true;
        }elseif ($checkUsername > 0){
            return false;
        } else
            return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'این نام کاربری قبلا توسط فروشگاه دیگری انتخاب شده است.';
    }
}
