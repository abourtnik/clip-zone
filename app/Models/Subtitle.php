<?php

namespace App\Models;

use App\Enums\SubtitleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\Intl\Languages;

/**
 * @mixin IdeHelperSubtitle
 */
class Subtitle extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => SubtitleStatus::class,
    ];

    public const string FILE_FOLDER = 'subtitles';


    /**
     * -------------------- RELATIONS --------------------
     */

    public function video () : BelongsTo {
        return $this->belongsTo(Video::class);
    }

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    protected function fileUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('video.subtitles', ['video' => $this->video, 'subtitle' => $this])
        );
    }

    protected function languageName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucfirst(Languages::getName($this->language, app()->getLocale())),
        );
    }

    protected function isPublic(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->status === SubtitleStatus::PUBLIC
        );
    }

    /**
     * Scope a query to only include valid videos.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublic(Builder $query): Builder
    {
        return $query->where('status', SubtitleStatus::PUBLIC);
    }
}
