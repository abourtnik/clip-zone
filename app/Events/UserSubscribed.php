<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSubscribed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public User $subscriber;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $subscriber)
    {
        $this->user = $user;
        $this->subscriber = $subscriber;
    }
}
