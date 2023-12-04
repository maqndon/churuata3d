<?php

namespace App\Policies;

use App\Models\User;

class CompanyPolicy
{
    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->email == 'admin@admin.com';
    }
}
