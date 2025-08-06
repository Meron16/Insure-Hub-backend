<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Events\UserStatusChanged;
use App\Listeners\NotifyUserAboutStatusChange;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Models\User;
use App\Observers\UserObserver;
use Symfony\Component\Mailer\Messenger\SendEmailMessage;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            UserRegistered::class => [
            SendEmailMessage::class,
            ],
            UserStatusChanged::class => [
            NotifyUserAboutStatusChange::class,
    ],
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
    User::observe(UserObserver::class);
    
    // Register other observers as needed
    // Policy::observe(PolicyObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }


}
