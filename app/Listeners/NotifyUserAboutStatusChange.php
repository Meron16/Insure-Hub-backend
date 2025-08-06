<?php

namespace App\Listeners;

use App\Events\UserStatusChanged;
use App\Notifications\AccountStatusChangedNotification;

class NotifyUserAboutStatusChange
{
    /**
     * Handle the event
     */
    public function handle(UserStatusChanged $event)
    {
        $user = $event->user;
        $previousStatus = $event->previousStatus;
        $newStatus = $user->account_status;

        // Only notify if status actually changed
        if ($previousStatus !== $newStatus) {
            $user->notify(new AccountStatusChangedNotification(
                $previousStatus,
                $newStatus
            ));
        }
    }
}