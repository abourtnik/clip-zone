<?php

namespace App\Events\Activity;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Model $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }
}
