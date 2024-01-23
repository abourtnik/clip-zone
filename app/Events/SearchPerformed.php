<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SearchPerformed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $query;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $query)
    {
        $this->query = $query;
    }
}
