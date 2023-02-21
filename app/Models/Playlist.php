<?php

namespace App\Models;

use App\Enums\PlaylistStatus;
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
        return $this->belongsToMany(Video::class, 'playlist_has_videos');
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
