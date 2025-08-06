<?php

namespace App\Observers;

use App\Models\User;
use App\Events\UserRegistered;
use App\Events\UserStatusChanged;

class UserObserver
{
    public function created(User $user)
    {
        // Send welcome email and create notification settings
        event(new UserRegistered($user));
        
        // Create default notification preferences
        $user->notificationPreferences()->create([
            'email_notifications' => true,
            'sms_notifications' => true,
            'push_notifications' => true,
        ]);

        // If user is a provider, create empty provider profile
        if ($user->role === 'provider') {
            $user->provider()->create([
                'company_name' => '',
                'license_number' => '',
                'contact_person' => $user->full_name,
                'contact_email' => $user->email,
                'contact_phone' => $user->phone_number,
            ]);
        }
    }

    public function updated(User $user)
    {
        // Check if account_status was changed
        if ($user->isDirty('account_status')) {
            event(new UserStatusChanged($user, $user->getOriginal('account_status')));
        }

        // Sync provider contact info if user details change
        if ($user->isDirty(['full_name', 'email', 'phone_number']) && $user->provider) {
            $user->provider->update([
                'contact_person' => $user->full_name,
                'contact_email' => $user->email,
                'contact_phone' => $user->phone_number,
            ]);
        }
    }

    public function deleting(User $user)
    {
        // Clean up related records before deletion
        $user->addresses()->delete();
        $user->notifications()->delete();
        $user->notificationPreferences()->delete();
        
        if ($user->provider) {
            $user->provider->bankDetails()->delete();
            $user->provider->documents()->delete();
            $user->provider->delete();
        }
    }
}