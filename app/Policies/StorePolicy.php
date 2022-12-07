<?php

namespace App\Policies;

use App\Cart;
use App\Store;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePolicy
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

    public function view(User $user , Store $store)
    {
        return $user->id == $store->user_id;
    }
}
