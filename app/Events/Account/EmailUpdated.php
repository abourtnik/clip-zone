<?php

namespace App\Events\Account;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $email;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
