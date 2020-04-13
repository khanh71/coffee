<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the material.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasAccess(['material.view']);
    }

    /**
     * Determine whether the user can create desks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['material.create']);
    }

    /**
     * Determine whether the user can update the material.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasAccess(['material.update']);
    }

    /**
     * Determine whether the user can delete the material.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasAccess(['material.delete']);
    }

}
