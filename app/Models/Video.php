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

    public function user (): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function category (): BelongsTo {
        return $this->belongsTo(Category::class);
    }

    public function views (): HasMany {
        return $this->hasMany(View::class);
    }

    public function getUrlAttribute() : string {
        return asset('storage/videos/'. $this->file);
    }

    public function getPosterUrlAttribute() : string {
        return asset('storage/thumbnails/'. $this->thumbnail);
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

    /*protected function views(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Number::humanize($value)
        );
    }*/

    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->description, '247', '...')
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

    protected function descriptionIsLong(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::length($this->description) > 780,
        );
    }

    protected function languageName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Languages::getName($this->language),
        );
    }

    public function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }

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

    public function isActive () : bool {
        return $this->status === VideoStatus::PUBLIC || ($this->status === VideoStatus::PLANNED && $this->publication_date->lte(now()));
    }

    public function isPlanned () : bool {
        return $this->status === VideoStatus::PLANNED && $this->publication_date->gt(now());
    }

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }
}
