<?php

namespace App\Policies;

use App\Models\policies;
use App\Models\User;

class PolicyPolicy
{
    public function viewAny(User $user)
    {
        return true; // All roles can view policies
    }

    public function view(User $user, policies $policy)
    {
        return true; // All roles can view individual policies
    }

    public function create(User $user)
    {
        return $user->role === 'provider';
    }

    public function update(User $user, policies $policy)
    {
        return $user->role === 'admin' || 
               ($user->role === 'provider' && $policy->provider->user_id === $user->user_id);
    }

    public function delete(User $user, policies $policy)
    {
        return $user->role === 'admin' || 
               ($user->role === 'provider' && $policy->provider->user_id === $user->user_id);
    }

    public function approve(User $user)
    {
        return $user->role === 'admin';
    }

    public function toggleActive(User $user)
    {
        return $user->role === 'admin';
    }
}