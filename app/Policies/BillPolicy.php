<?php

namespace App\Policies;

use App\Bill;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillPolicy
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

    public function view(User $user , Bill $bill)
    {
        return $user->id == $bill->user_id;
    }

    public function delete(User $user , Bill $bill)
    {
        return $user->id == $bill->user_id;
    }

    public function sellView(User $user , Bill $bill)
    {
        return $user->id == $bill->store->user_id;
    }

    public function sellDelete(User $user , Bill $bill)
    {
        return $user->id == $bill->store->user_id;
    }

    public function makeDelivered(User $user , Bill $bill)
    {
        return $user->id == $bill->user_id;
    }

}
