<?php

namespace App\Policies;

use App\Cart;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CartPolicy
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

    public function delete(User $user, Cart $cart)
    {
        return $cart->user->id === $user->id;
    }

    public function increaseOrDecrease(User $user , Cart $cart)
    {
        return $cart->user->id === $user->id;
    }
}
