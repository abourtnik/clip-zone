<?php

namespace App\Logs;

use Opcodes\LogViewer\Logs\Log;

class CronLog extends Log
{
    public static string $name = 'Cron';

    public static string $regex = '/^(?<datetime>\S+[\s\d:]+) - (?<message>.*)/';

    /** @var array|\string[][] The columns displayed on the frontend, and which data they should display */
    public static array $columns = [
        ['label' => 'Datetime', 'data_path' => 'datetime'],
        ['label' => 'Message', 'data_path' => 'message'],
    ];

    public function fillMatches(array $matches = []): void
    {
        // The parent class already handles the "datetime", "level" and "message" matches. But you're free to assign them yourself.
        // If you added custom regex named groups, you'll have to assign them to the `$this->context` array below.
        parent::fillMatches($matches);
    }
}
