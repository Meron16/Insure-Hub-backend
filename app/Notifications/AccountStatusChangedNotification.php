<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $previousStatus;
    public $newStatus;

    public function __construct($previousStatus, $newStatus)
    {
        $this->previousStatus = $previousStatus;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your Account Status Has Changed')
            ->line('Your account status has been updated.')
            ->line("Previous status: {$this->formatStatus($this->previousStatus)}")
            ->line("New status: {$this->formatStatus($this->newStatus)}")
            ->action('View Your Account', url('/account'))
            ->line('Thank you for using our service!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Your account status changed from {$this->formatStatus($this->previousStatus)} to {$this->formatStatus($this->newStatus)}",
            'url' => '/account',
        ];
    }

    protected function formatStatus($status)
    {
        return ucfirst(str_replace('_', ' ', $status));
    }
}