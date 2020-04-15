<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the report.
     *
     * @param  \App\User  $user
     * @param  \App\report  $report
     * @return mixed
     */
    public function cost(User $user)
    {
        return $user->hasAccess(['report.cost']);
    }

    /**
     * Determine whether the user can create reports.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function profit(User $user)
    {
        return $user->hasAccess(['report.profit']);
    }

    /**
     * Determine whether the user can update the report.
     *
     * @param  \App\User  $user
     * @param  \App\report  $report
     * @return mixed
     */
    public function salary(User $user)
    {
        return $user->hasAccess(['report.salary']);
    }

    /**
     * Determine whether the user can delete the report.
     *
     * @param  \App\User  $user
     * @param  \App\report  $report
     * @return mixed
     */
    public function sell(User $user)
    {
        return $user->hasAccess(['report.sell']);
    }


    public function shop(User $user)
    {
        return $user->hasAccess(['shop.update']);
    }
}
