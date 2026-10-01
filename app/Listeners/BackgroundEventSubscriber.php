<?php

namespace App\Listeners;

use Illuminate\Console\Events\ScheduledTaskFailed;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Mail\Message;

class BackgroundEventSubscriber
{
    /**
     * Handle when a job failed
     */
    public function handleJobFailed(JobFailed $event): void {

        $message = $event->exception->getMessage(). ' at ' . $event->exception->getFile(). ':' .$event->exception->getLine();

        Mail::raw($message, function (Message $message) use ($event) {
            $message->to(config('mail.server_mail'))->subject($event->job->resolveName(). ' - FAILED');
        });
    }

    /**
     * Handle when a scheduled task failed
     */
    public function handleScheduledTaskFailed(ScheduledTaskFailed $event): void {

        $message = $event->exception->getMessage(). ' at ' . $event->exception->getFile(). ':' .$event->exception->getLine();

        Mail::raw($message, function (Message $message) use ($event) {
            $message->to(config('mail.server_mail'))->subject($event->task->mutexName(). ' - FAILED');
        });
    }
}
