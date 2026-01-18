<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SearchPerformed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $query;
    public int $results;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $query, int $results = 0)
    {
        $this->query = $query;
        $this->results = $results;
    }
}
