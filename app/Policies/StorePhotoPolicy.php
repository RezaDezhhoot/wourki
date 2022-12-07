<?php

namespace App\Policies;

use App\Cart;
use App\Product_seller_photo;
use App\Store;
use App\Store_photo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StorePhotoPolicy
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

    public function delete(User $user , Store_photo $store_photo)
    {
        return $user->id == $store_photo->store->user_id;
    }
}
