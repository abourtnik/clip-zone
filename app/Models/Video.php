<?php

namespace App\Models;

use App\Enums\VideoStatus;
use App\Models\Traits\HasLike;
use App\Models\Interfaces\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Symfony\Component\Intl\Languages;

class Video extends Model implements Likeable
{
    use HasFactory, HasLike;

    protected $guarded = ['id'];

    protected $dates = [
        'publication_date'
    ];

    protected $casts = [
        'status' => VideoStatus::class
    ];

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

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('storage/videos/'. $this->file)
        );
    }

    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => asset('storage/thumbnails/'. $this->thumbnail)
        );
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::PUBLIC || ($this->status === VideoStatus::PLANNED && $this->publication_date->lte(now())) || $this->status === VideoStatus::UNLISTED
        );
    }

    protected function isPublic(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::PUBLIC || ($this->status === VideoStatus::PLANNED && $this->publication_date->lte(now()))
        );
    }

    protected function isPlanned(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === VideoStatus::PLANNED && $this->publication_date->gt(now())

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
        $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';

        $a = clean(preg_replace($url, '<a href="$0" target="_blank" title="$0" rel="external nofollow">$0</a>', Str::limit($this->description, 247)));

        return Attribute::make(
            get: fn () => $a
        );
    }

    protected function parsedDescription(): Attribute
    {
        $url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';

        return Attribute::make(
            get: fn () => clean(preg_replace($url, '<a href="$0" target="_blank" title="$0" rel="external nofollow" target="_blank">$0</a>', $this->description))
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
            get: fn () => $this->isActive() ? VideoStatus::PUBLIC : $this->status
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn () => route('video.show', $this),
        );
    }

    protected function languageName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Languages::getName($this->language),
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
        $query->where('status', VideoStatus::PUBLIC)
            ->orWhere(function($query) {
                $query->where('status', VideoStatus::PLANNED)
                    ->where('publication_date', '<=', now());
            });
    }

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }
}
