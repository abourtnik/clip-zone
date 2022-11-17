<?php

namespace App\Models\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface Likeable
{
    public function interactions(): MorphMany;
    public function likes(): MorphMany;
    public function dislikes(): MorphMany;
}
