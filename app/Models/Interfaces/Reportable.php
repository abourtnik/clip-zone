<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Reportable
{
    public function reports(): MorphMany;
}
