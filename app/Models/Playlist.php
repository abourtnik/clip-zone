<?php

namespace App\Models;

use App\Enums\PlaylistStatus;
use App\Models\Pivots\FavoritePlaylist;
use App\Models\Pivots\PlaylistVideo;
use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * @mixin IdeHelperPlaylist
 */
class Playlist extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => PlaylistStatus::class,
        'is_deletable' => 'boolean'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function videos() : BelongsToMany
    {
        return $this->belongsToMany(Video::class, 'playlist_has_videos')
            ->using(PlaylistVideo::class)
            ->orderByPivot('position', 'asc');
    }

    public function users () : BelongsToMany {
        return $this->belongsToMany(User::class, 'favorites_playlist', 'user_id', 'playlist_id')
            ->using(FavoritePlaylist::class)
            ->withPivot(['added_at']);
    }

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn () => route('playlist.show', $this),
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => 'playlist',
        );
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === PlaylistStatus::PUBLIC || $this->status === PlaylistStatus::UNLISTED
        );
    }

    protected function firstVideo(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos->first(function (Video $video, int $key) {
                return $video->is_public || $video->user->is(Auth::user());
            })
        );
    }

    /**
     * -------------------- SCOPES --------------------
     */

    /**
     * Scope a query to only include active videos.
     *
     * @param  Builder $query
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', PlaylistStatus::PUBLIC);
    }
}
