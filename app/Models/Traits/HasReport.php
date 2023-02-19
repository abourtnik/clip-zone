<?php

namespace App\Models\Traits;

use App\Models\Report;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasReport
{
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
