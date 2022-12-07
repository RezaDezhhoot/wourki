<?php

namespace App\Policies;

use App\Cart;
use App\ProductSeller;
use App\Store;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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

    public function view(User $user , ProductSeller $product)
    {
        return $user->id == $product->store->user_id;
    }

    public function delete(User $user , ProductSeller $product)
    {
        return $user->id == $product->store->user_id;
    }
}
