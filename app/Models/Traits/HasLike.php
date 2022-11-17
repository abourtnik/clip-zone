<?php

namespace App\Models\Traits;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLike
{
    public function interactions(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function likes(): MorphMany
    {
        return $this->interactions()->where('status', true);
    }

    public function dislikes(): MorphMany
    {
        return $this->interactions()->where('status', false);
    }
}
