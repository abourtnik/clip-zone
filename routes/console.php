<?php

use App\Console\Commands\DeleteExpiredChunks;
use App\Console\Commands\DeleteUnconfirmedUsers;
use App\Console\Commands\Premium\SendCardExpiration;
use App\Console\Commands\Premium\SendTrialsEnd;
use App\Console\Commands\SendVideoPublishedEvent;
use Laravel\Scout\Console\ImportCommand;
use Illuminate\Support\Facades\Schedule;

$LOG_PATH = storage_path('logs/cron.log');

// ACCOUNTS
Schedule::command(DeleteUnconfirmedUsers::class)
    ->hourly()
    ->appendOutputTo($LOG_PATH);

// Clear expired tokens
Schedule::command('sanctum:prune-expired')
    ->daily()
    ->appendOutputTo($LOG_PATH);

// Deleting Expired Password Reset Tokens
Schedule::command('auth:clear-resets')
    ->everyFifteenMinutes()
    ->appendOutputTo($LOG_PATH);

// PREMIUM
Schedule::command(SendTrialsEnd::class)
    ->dailyAt('12:00')
    ->appendOutputTo($LOG_PATH);

Schedule::command(SendCardExpiration::class)
    ->twiceMonthly(1, 16, '09:30')
    ->lastDayOfMonth('09:30')
    ->appendOutputTo($LOG_PATH);

// VIDEOS
Schedule::command(DeleteExpiredChunks::class)
    ->dailyAt('4:00')
    ->appendOutputTo($LOG_PATH);


Schedule::command(SendVideoPublishedEvent::class)
    ->everyMinute()
    ->appendOutputTo($LOG_PATH);

// Sync views_count for videos index Meilisearch
Schedule::command(ImportCommand::class, ['App\Models\Video'])
    ->dailyAt('3:00')
    ->appendOutputTo($LOG_PATH);
