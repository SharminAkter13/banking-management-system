<?php

namespace App\Policies;

namespace App\Policies;

use App\Models\User;
use App\Models\Role;

class UserPolicy
{
    // Check if a user can view any user (Admin role)
    public function viewAny(User $user)
    {
        return $user->role->slug === 'admin'; // or any other condition based on roles
    }

    // Check if a user can view a single user (Admin or the user themselves)
    public function view(User $user, User $model)
    {
        return $user->role->slug === 'admin' || $user->id === $model->id;
    }

    // Check if a user can create a new user (Admin role)
    public function create(User $user)
    {
        return $user->role->slug === 'admin';
    }

    // Check if a user can update another user (Admin or the user themselves)
    public function update(User $user, User $model)
    {
        return $user->role->slug === 'admin' || $user->id === $model->id;
    }

    // Check if a user can delete a user (Admin role)
    public function delete(User $user, User $model)
    {
        return $user->role->slug === 'admin';
    }
}
