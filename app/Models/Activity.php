<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HasFactory, Filterable;

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => strtolower(class_basename($this->subject)) . ($this->subject->likeable ? '_'.strtolower(class_basename($this->subject->likeable)) : ''),
        );
    }
}
