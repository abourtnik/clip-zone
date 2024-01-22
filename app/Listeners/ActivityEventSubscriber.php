<?php

namespace App\Listeners;

use App\Events\Activity\ActivityCreated;
use App\Events\Activity\ActivityDeleted;
use App\Models\Activity;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Auth;

class ActivityEventSubscriber
{
    /**
     * Handle user have new activity event.
     */
    public function saveActivity(ActivityCreated $event): void {
          Auth::user()->activity()->create([
              'subject_type' => get_class($event->model),
              'subject_id' => $event->model->id,
              'perform_at' => $event->model->created_at
          ]);
    }

    /**
     * Handle user delete activity event.
     */
    public function deleteActivity(ActivityDeleted $event): void {
        Activity::query()
            ->where('subject_id', $event->model->id)
            ->delete();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @return array<string, string>
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            ActivityCreated::class => 'saveActivity',
            ActivityDeleted::class => 'deleteActivity',
        ];
    }
}
