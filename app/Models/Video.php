<?php

namespace App\Models;

use App\Enums\VideoStatus;
use App\Helpers\Number;
use App\Models\Traits\HasLike;
use App\Models\Interfaces\Likeable;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Video extends Model implements Likeable
{
    use HasFactory, HasLike;

    protected $guarded = ['id'];

    protected $dates = [
        'publication_date'
    ];

    public const MAX_VIDEO_SIZE = '15mo';
    public const MAX_VIDEO_THUMBNAIL = '5mo';

    public function user (): BelongsTo {
        return $this->belongsTo(User::class);
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
            get: fn ($value) => CarbonInterval::seconds($value)
                                ->cascade()
                                ->forHumans([
                                    'join' => fn ($list) => implode(':', array_map(fn($part) => substr($part, 0, -1), $list)),
                                    'short' => true,
                                    'parts' => 3,
                                    'minimumUnit' => 's',
                                    //'options' => CarbonInterface::NO_ZERO_DIFF
                                ])
        );
    }

    protected function views(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Number::humanize($value)
        );
    }

    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->description, '247', '...')
        );
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
        return $this->status === VideoStatus::PUBLIC->value || ($this->status === VideoStatus::PLANNED->value && $this->publication_date->lte(now()));
    }

    public function isPlanned () : bool {
        return $this->status === VideoStatus::PLANNED->value && $this->publication_date->gt(now());
    }
}
