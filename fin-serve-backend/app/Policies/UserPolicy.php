<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $authUser)
    {
        return $authUser->role->slug === 'admin';
    }

    public function view(User $authUser, User $user)
    {
        return $authUser->role->slug === 'admin' || $authUser->id === $user->id;
    }

    public function create(User $authUser)
    {
        return $authUser->role->slug === 'admin';
    }

    public function update(User $authUser, User $user)
    {
        return $authUser->role->slug === 'admin' || $authUser->id === $user->id;
    }

    public function delete(User $authUser, User $user)
    {
        return $authUser->role->slug === 'admin';
    }
}
