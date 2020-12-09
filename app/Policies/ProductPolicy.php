<?php

namespace App\Policies;

use App\Models\Tag;
use App\User;
use App\Models\ProductNew;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the product new.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductNew  $productNew
     * @return mixed
     */
    public function view(User $user, ProductNew $productNew)
    {
        return true;
    }

    /**
     * Determine whether the user can create product news.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the product new.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductNew  $productNew
     * @return mixed
     */
    public function update(User $user, ProductNew $productNew)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the product new.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductNew  $productNew
     * @return mixed
     */
    public function delete(User $user, ProductNew $productNew)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the product new.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductNew  $productNew
     * @return mixed
     */
    public function restore(User $user, ProductNew $productNew)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the product new.
     *
     * @param  \App\User  $user
     * @param  \App\Models\ProductNew  $productNew
     * @return mixed
     */
    public function forceDelete(User $user, ProductNew $productNew)
    {
        return false;
    }

    public function attachAnyTag(User $user, ProductNew $productNew)
    {
        return false;
    }

    public function attachTag(User $user, ProductNew $productNew, Tag $tag)
    {
        return false;
    }

    public function detachTag(User $user, ProductNew $productNew, Tag $tag)
    {
        return false;
    }
}
