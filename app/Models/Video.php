<?php

namespace App\Models;

use App\Enums\VideoStatus;
use App\Helpers\Parser;
use App\Models\Interfaces\Reportable;
use App\Models\Traits\Filterable;
use App\Models\Traits\HasLike;
use App\Models\Interfaces\Likeable;
use App\Models\Traits\HasReport;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Staudenmeir\EloquentEagerLimit\HasEagerLimit;
use Symfony\Component\Intl\Languages;

class Video extends Model implements Likeable, Reportable
{
    use HasFactory, HasLike, HasReport, HasEagerLimit, Filterable;

    protected $guarded = ['id'];

    protected $dates = [
        'publication_date',
        'scheduled_date',
        'banned_at',
        'uploaded_at',
    ];

    protected $casts = [
        'status' => VideoStatus::class,
    ];

    public const THUMBNAIL_FOLDER = 'thumbnails';
    public const VIDEO_FOLDER = 'videos';
    public const CHUNK_FOLDER = 'chunks';

    public const AVAILABLE_LANGUAGES = ['ar', 'en', 'fr', 'zh', 'nl', 'de', 'hi', 'it', 'ko', 'es', 'pt', 'ru'];

    public static function languages (): array {
        return array_filter(Languages::getNames(app()->getLocale()), function ($code) {
            return in_array($code, self::AVAILABLE_LANGUAGES);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * -------------------- RELATIONS --------------------
     */

    public function user (): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function category (): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function views (): HasMany {
        return $this->hasMany(View::class);
    }

    public function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function pinned_comment () : HasOne {
        return $this->hasOne(Comment::class, 'id', 'pinned_comment_id');
    }

    public function comment_interactions (): HasManyThrough {
        return $this->hasManyThrough(Interaction::class, Comment::class, 'video_id', 'likeable_id')
            ->where('likeable_type', Comment::class);
    }

    public function playlists() : BelongsToMany {
        return $this->belongsToMany(Playlist::class, 'playlist_has_videos');
    }

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('video.file', $this)
        );
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () =>  $this->thumbnail ? route('video.thumbnail', $this) : null
        );
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                !$this->is_uploading &&
                (
                    $this->status === VideoStatus::PUBLIC ||
                    ($this->status === VideoStatus::PLANNED && $this->scheduled_date->lte(now()))
                )

        );
    }

    protected function isPublic(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                !$this->is_uploading &&
                (
                    $this->status === VideoStatus::PUBLIC ||
                    ($this->status === VideoStatus::PLANNED && $this->scheduled_date->lte(now())) ||
                    $this->status === VideoStatus::UNLISTED
                )

        );
    }

    protected function isCreated(): Attribute
    {
        return Attribute::make(
            get: fn () => !in_array($this->status, [VideoStatus::DRAFT, VideoStatus::FAILED]) && !$this->is_uploading
        );
    }

    protected function isPlanned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::PLANNED && $this->scheduled_date->gt(now())

        );
    }

    protected function isUnlisted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::UNLISTED

        );
    }

    protected function isPrivate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::PRIVATE

        );
    }

    protected function isDraft(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::DRAFT

        );
    }

    protected function isBanned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::BANNED

        );
    }

    protected function isUploading(): Attribute
    {
        return Attribute::make(
            get: fn () => is_null($this->uploaded_at)

        );
    }

    protected function isFailed(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::FAILED

        );
    }

    protected function duration(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value >= 3600) {
                    return  floor($value / 3600). ':' .sprintf("%02d", floor(($value % 3600) / 60)). ':' .sprintf("%02d", ($value % 3600) % 60);
                }
                else {
                    return floor($value / 60).':'. sprintf("%02d", $value % 60);
                }
            }
        );
    }

    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Parser::applyParsers(Str::limit($this->description, 200), ['links', 'timecodes'])
        );
    }

    protected function parsedDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Parser::applyParsers($this->description, ['links', 'timecodes'])
        );
    }

    protected function descriptionIsLong(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::length($this->description) > 247,
        );
    }

    protected function shortTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->title, '69', '...')
        );
    }

    protected function realStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::PUBLIC || ($this->status === VideoStatus::PLANNED && $this->scheduled_date->lte(now())) ? VideoStatus::PUBLIC : $this->status
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn () => route('video.show', ['slug' => $this->slug, 'video' => $this]),
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => 'video',
        );
    }

    protected function isPinned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user->pinned_video?->is($this)
        );
    }

    protected function languageName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst(Languages::getName($this->language, app()->getLocale())),
        );
    }

    /**
     * -------------------- SCOPES --------------------
     */

    /**
     * Scope a query to only include active videos.
     *
     * @param QueryBuilder|EloquentBuilder $query
     * @return QueryBuilder|EloquentBuilder
     */
    public function scopeActive(QueryBuilder|EloquentBuilder $query): QueryBuilder|EloquentBuilder
    {
        return $query->where('status', VideoStatus::PUBLIC)
            ->whereNotNull('uploaded_at')
            ->orWhere(function($query) {
                $query->where('status', VideoStatus::PLANNED)
                    ->where('scheduled_date', '<=', now());
            });
    }

    /**
     * Scope a query to only include public videos.
     *
     * @param QueryBuilder|EloquentBuilder $query
     * @return QueryBuilder|EloquentBuilder
     */
    public function scopePublic(QueryBuilder|EloquentBuilder $query, $includeAuthVideo = false): QueryBuilder|EloquentBuilder
    {
        return $query->whereIn('status', [VideoStatus::PUBLIC, VideoStatus::UNLISTED])
            ->whereNotNull('uploaded_at')
            ->orWhere(function($query) use ($includeAuthVideo) {
                $query->where('status', VideoStatus::PLANNED)
                    ->where('scheduled_date', '<=', now())
                    ->when($includeAuthVideo, fn($q) => $q->orWhere('user_id', Auth::id()));
            });
    }

    /**
     * Scope a query to only include not active videos.
     *
     * @param QueryBuilder|EloquentBuilder $query
     * @return QueryBuilder|EloquentBuilder
     */
    public function scopeNotActive(QueryBuilder|EloquentBuilder $query): QueryBuilder|EloquentBuilder
    {
        return $query->whereIn('status', [VideoStatus::PRIVATE, VideoStatus::BANNED, VideoStatus::DRAFT, VideoStatus::FAILED])
            ->orWhere(function($query) {
                $query->where('status', VideoStatus::PLANNED)
                    ->where('scheduled_date', '>', now());
            })
            ->orWhereNull('uploaded_at');
    }

    /**
     * Scope a query to only include valid videos.
     *
     * @param QueryBuilder|EloquentBuilder $query
     * @return QueryBuilder|EloquentBuilder
     */
    public function scopeValid(QueryBuilder|EloquentBuilder $query): QueryBuilder|EloquentBuilder
    {
        return $query->whereNot('status', VideoStatus::FAILED);
    }
}
