<?php

namespace App\Models;

use App\Enums\PlaylistStatus;
use App\Models\Pivots\FavoritePlaylist;
use App\Models\Pivots\PlaylistVideo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Playlist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => PlaylistStatus::class,
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

    protected function thumbnail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos()->public(true)->first()?->thumbnail_url
        );
    }

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

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }
}
