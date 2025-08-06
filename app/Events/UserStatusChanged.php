<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatusChanged
{
    use Dispatchable, SerializesModels;

    public $user;
    public $previousStatus;

    public function __construct(User $user, $previousStatus)
    {
        $this->user = $user;
        $this->previousStatus = $previousStatus;
    }
}