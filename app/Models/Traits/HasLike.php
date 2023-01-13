<?php

namespace App\Models\Traits;

use App\Models\Interaction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLike
{
    public function interactions(): MorphMany
    {
        return $this->morphMany(Interaction::class, 'likeable');
    }

    public function likes(): MorphMany
    {
        return $this->interactions()->where('status', true);
    }

    public function dislikes(): MorphMany
    {
        return $this->interactions()->where('status', false);
    }

    protected function likesRatio(): Attribute
    {
        return Attribute::make(
            get: fn () => round(($this->likes_count / ($this->interactions_count ?: 1)) * 100, 0)
        );
    }

    protected function dislikesRatio(): Attribute
    {
        return Attribute::make(
            get: fn () => round(($this->dislikes_count / ($this->interactions_count ?: 1)) * 100, 0)
        );
    }
}
