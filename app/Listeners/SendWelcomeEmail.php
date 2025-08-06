<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(UserRegistered $event)
    {
        if ($event->user->email) {
            Mail::to($event->user->email)
                ->send(new WelcomeEmail($event->user));
        }
    }
}