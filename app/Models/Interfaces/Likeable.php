<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Likeable
{
    public function likes(): MorphMany;
}
