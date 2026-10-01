<?php

namespace App\Listeners;

use App\Events\Activity\ActivityCreated;
use App\Events\Activity\ActivityDeleted;
use App\Events\Activity\ActivityUpdated;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityEventSubscriber
{
    /**
     * Handle user has a new activity event.
     */
    public function handleActivityCreated(ActivityCreated $event): void {
      Auth::user()->activity()->create([
          'subject_type' => get_class($event->model),
          'subject_id' => $event->model->id,
          'perform_at' => $event->model->created_at
      ]);
    }

    /**
     * Handle user has updated activity event.
     */
    public function handleActivityUpdated(ActivityUpdated $event): void {
        Auth::user()->activity()->where([
            'subject_type' => get_class($event->model),
            'subject_id' => $event->model->id,
        ])->update([
            'perform_at' => $event->model->created_at
        ]);
    }

    /**
     * Handle user delete activity event.
     */
    public function handleActivityDeleted(ActivityDeleted $event): void {
        Activity::query()
            ->where('subject_id', $event->model->id)
            ->delete();
    }
}
