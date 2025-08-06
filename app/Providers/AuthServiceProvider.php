<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\policies;
use App\Models\Provider;
use App\Models\User;
use App\Policies\PolicyPolicy;
use App\Policies\ProviderPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    User::class => UserPolicy::class,
    Provider::class => ProviderPolicy::class,
    policies::class => PolicyPolicy::class,
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
