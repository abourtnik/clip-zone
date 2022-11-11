<?php

namespace App\Models\Traits;

use App\Models\Like;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLike
{
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /*
    public function interactions () : BelongsToMany {
        return $this->belongsToMany(User::class, 'likes', 'video_id', 'user_id');
    }

    public function likes () : BelongsToMany {
        return $this->interactions()->wherePivot('status', true);
    }

    public function dislikes () : BelongsToMany {
        return $this->interactions()->wherePivot('status', false);
    }
    */
}
