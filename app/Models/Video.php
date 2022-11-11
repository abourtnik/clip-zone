<?php

namespace App\Models;

use App\Models\Traits\HasLike;
use App\Models\Interfaces\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model implements Likeable
{
    use HasFactory, HasLike;

    protected $guarded = ['id'];

    public function user (): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute() : string {
        return asset('storage/videos/'. $this->file);
    }

    public function getPosterUrlAttribute() : string {
        return asset('storage/posters/'. $this->poster);
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

    public function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }
}
