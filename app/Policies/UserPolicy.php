<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->role === 'admin';
    }

    public function view(User $user, User $model)
    {
        return $user->role === 'admin' || $user->user_id === $model->user_id;
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $model)
    {
        return $user->role === 'admin' || $user->user_id === $model->user_id;
    }

    public function delete(User $user, User $model)
    {
        return $user->role === 'admin';
    }

    public function changeStatus(User $user)
    {
        return $user->role === 'admin';
    }
}