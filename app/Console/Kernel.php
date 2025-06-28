<?php

namespace App\Console;

use App\Console\Commands\DeleteExpiredChunks;
use App\Console\Commands\DeleteUnconfirmedUsers;
use App\Console\Commands\Premium\SendCardExpiration;
use App\Console\Commands\Premium\SendTrialsEnd;
use App\Console\Commands\SendVideoPublishedEvent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Scout\Console\ImportCommand;

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
        $LOG_PATH = storage_path('logs/cron.log');

        $schedule->command(SendVideoPublishedEvent::class)
            ->everyMinute()
            ->appendOutputTo($LOG_PATH);
        $schedule->command(SendTrialsEnd::class)
            ->dailyAt('12:00')
            ->appendOutputTo($LOG_PATH);
        $schedule->command(DeleteUnconfirmedUsers::class)
            ->dailyAt('1:00')
            ->appendOutputTo($LOG_PATH);
        $schedule->command(DeleteExpiredChunks::class)
            ->dailyAt('2:00')
            ->appendOutputTo($LOG_PATH);
        $schedule->command(SendCardExpiration::class)
            ->twiceMonthly(1, 16, '09:30')
            ->lastDayOfMonth('09:30')
            ->appendOutputTo($LOG_PATH);

        // Sync views_count for videos index Meilisearch
        $schedule->command(ImportCommand::class, ['App\Models\Video'])
            ->dailyAt('3:00');

        // Clear expired tokens
        $schedule->command('sanctum:prune-expired')
            ->daily()
            ->appendOutputTo($LOG_PATH);

        // Deleting Expired Password Reset Tokens
        $schedule->command('auth:clear-resets')
            ->everyFifteenMinutes()
            ->appendOutputTo($LOG_PATH);
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
