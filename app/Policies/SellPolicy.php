<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SellPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the sell.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasAccess(['sell.view']);
    }

    /**
     * Determine whether the user can create desks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['sell.create']);
    }

    /**
     * Determine whether the user can delete the sell.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasAccess(['sell.delete']);
    }

    public function pay(User $user)
    {
        return $user->hasAccess(['sell.pay']);
    }

    public function merge(User $user)
    {
        return $user->hasAccess(['sell.merge']);
    }
}
