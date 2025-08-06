<?php

namespace App\Policies;

use App\Models\Provider;
use App\Models\User;

class ProviderPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role, ['admin', 'provider']);
    }

    public function view(User $user, Provider $provider)
    {
        return $user->role === 'admin' || 
               ($user->role === 'provider' && $user->user_id === $provider->user_id);
    }

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Provider $provider)
    {
        return $user->role === 'admin' || 
               ($user->role === 'provider' && $user->user_id === $provider->user_id);
    }

    public function delete(User $user, Provider $provider)
    {
        return $user->role === 'admin';
    }

    public function approve(User $user)
    {
        return $user->role === 'admin';
    }
}