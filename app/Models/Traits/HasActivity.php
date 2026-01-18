<?php

namespace App\Models\Traits;

use App\Events\Activity\ActivityCreated;
use App\Events\Activity\ActivityDeleted;
use App\Events\Activity\ActivityUpdated;
use Illuminate\Database\Eloquent\Model;

trait HasActivity
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::created(function (Model $model) {
            ActivityCreated::dispatch($model);
        });

        static::updated(function (Model $model) {
            ActivityUpdated::dispatch($model);
        });

        static::deleted(function (Model $model) {
            ActivityDeleted::dispatch($model);
        });
    }
}
