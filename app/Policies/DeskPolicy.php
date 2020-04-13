<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the desk.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasAccess(['desk.view']);
    }

    /**
     * Determine whether the user can create desks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['desk.create']);
    }

    /**
     * Determine whether the user can update the desk.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasAccess(['desk.update']);
    }

    /**
     * Determine whether the user can delete the desk.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasAccess(['desk.delete']);
    }

}
