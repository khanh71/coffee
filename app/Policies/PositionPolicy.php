<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PositionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the position.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasAccess(['position.view']);
    }

    /**
     * Determine whether the user can create desks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['position.create']);
    }

    /**
     * Determine whether the user can update the position.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasAccess(['position.update']);
    }

    /**
     * Determine whether the user can delete the position.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasAccess(['position.delete']);
    }

    public function role(User $user)
    {
        return $user->hasAccess(['position.role']);
    }

}
