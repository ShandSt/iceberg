<?php

namespace App\Policies;

use App\User;
use App\Models\ConfirmCode;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConfirmCodePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the confirm code.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ConfirmCode  $confirmCode
     * @return mixed
     */
    public function view(User $user, ConfirmCode $confirmCode)
    {
        return true;
    }

    /**
     * Determine whether the user can create confirm codes.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the confirm code.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ConfirmCode  $confirmCode
     * @return mixed
     */
    public function update(User $user, ConfirmCode $confirmCode)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the confirm code.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ConfirmCode  $confirmCode
     * @return mixed
     */
    public function delete(User $user, ConfirmCode $confirmCode)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the confirm code.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ConfirmCode  $confirmCode
     * @return mixed
     */
    public function restore(User $user, ConfirmCode $confirmCode)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the confirm code.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ConfirmCode  $confirmCode
     * @return mixed
     */
    public function forceDelete(User $user, ConfirmCode $confirmCode)
    {
        return false;
    }
}
