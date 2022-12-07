<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;

class UserMustBeValidAndHasNotStore implements Rule
{
    private $error;
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
     * @param  mixed  $userId
     * @return bool
     */
    public function passes($attribute, $userId)
    {
        if(!$userId){
            return true;
        }
        $user = User::find($userId);
        if(!$user){
            $this->error = 'کاربر نامعتبر است.';
            return false;
        }
        if(count($user->stores) == 0){
            $this->error = 'کاربر انتخاب شده دارای فروشگاه نیست.';
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error;
    }
}
