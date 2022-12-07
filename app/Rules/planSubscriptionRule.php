<?php

namespace App\Rules;

use App\Plan;
use Illuminate\Contracts\Validation\Rule;

class planSubscriptionRule implements Rule
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
        if ($value === '' && $value === null){
            return true;
        }
        if ($value == 'all'){
            return true;
        }
        $billSub = Plan::where('id' , $value)->exists();
        if ($billSub){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'پلن نامعتبر است.';
    }
}
