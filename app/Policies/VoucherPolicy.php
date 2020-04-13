<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the voucher.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasAccess(['voucher.view']);
    }

    /**
     * Determine whether the user can create desks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['voucher.create']);
    }

    /**
     * Determine whether the user can update the voucher.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasAccess(['voucher.update']);
    }

    /**
     * Determine whether the user can delete the voucher.
     *
     * @param  \App\User  $user
     * @param  \App\Desk  $desk
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasAccess(['voucher.delete']);
    }

}
