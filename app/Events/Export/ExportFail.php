<?php

namespace App\Events\Export;

use App\Models\Export;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExportFail
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public Export $export;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Export $export)
    {
        $this->user = $user;
        $this->export = $export;
    }
}
