<?php

namespace App\Policies;

use App\Cart;
use App\Product_seller_photo;
use App\ProductSeller;
use App\Store;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPhotoPolicy
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

    public function delete(User $user , Product_seller_photo $photo)
    {
        return $user->id == $photo->product->store->user_id;
    }
}
