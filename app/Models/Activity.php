<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => strtolower(class_basename($this->subject)) . ($this->subject->likeable ? '_'.strtolower(class_basename($this->subject->likeable)) : ''),
        );
    }

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }
}
