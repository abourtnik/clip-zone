<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    protected function type(): Attribute
    {
        $class = explode( '\\', get_class($this->subject));

        return Attribute::make(
            get: fn () => strtolower(end($class)),
        );
    }
}
