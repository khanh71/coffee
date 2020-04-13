<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductCatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the productcate.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasAccess(['productcate.view']);
    }

    /**
     * Determine whether the user can create desks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['productcate.create']);
    }

    /**
     * Determine whether the user can update the productcate.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasAccess(['productcate.update']);
    }

    /**
     * Determine whether the user can delete the productcate.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasAccess(['productcate.delete']);
    }

}
