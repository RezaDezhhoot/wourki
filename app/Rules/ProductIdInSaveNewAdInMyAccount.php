<?php

namespace App\Rules;

use App\Products;
use App\ProductSeller;
use App\Store;
use Illuminate\Contracts\Validation\Rule;

class ProductIdInSaveNewAdInMyAccount implements Rule
{
    private $msg;
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
        $productIdExisted = ProductSeller::join('store' , 'product_seller.store_id' , '=' , 'store.id')
            ->where('store.user_id' , $user->id)
            ->where('product_seller.id' , $value)
            ->exists();
        if($productIdExisted){
            $this->msg = 'محصول نامعتبر است.';
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}
