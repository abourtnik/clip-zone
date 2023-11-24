<?php

namespace App\Console;

use App\Console\Commands\DeleteExpiredChunks;
use App\Console\Commands\DeleteUnconfirmedUsers;
use App\Console\Commands\SendTrialsEnd;
use App\Console\Commands\SendVideoPublishedEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) : void
    {
        $schedule->command(SendVideoPublishedEvent::class)->everyMinute();
        $schedule->command(SendTrialsEnd::class)->dailyAt('12:00');
        $schedule->command(DeleteUnconfirmedUsers::class)->dailyAt('1:00');
        $schedule->command(DeleteExpiredChunks::class)->dailyAt('2:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands() :void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
