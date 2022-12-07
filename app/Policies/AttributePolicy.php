<?php

namespace App\Policies;

use App\Product_seller_attribute;
use App\ProductSeller;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttributePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function delete(User $user , Product_seller_attribute $attribute)
    {
        return $user->id == $attribute->product->store->user_id;
    }

}
