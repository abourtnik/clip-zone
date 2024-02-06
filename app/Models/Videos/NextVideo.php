<?php

namespace App\Models\Videos;
use Illuminate\Database\Eloquent\Casts\Attribute;

interface NextVideo
{
    public function route(): Attribute;
}
