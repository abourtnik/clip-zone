<?php

namespace App\Events\Account;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }
}
