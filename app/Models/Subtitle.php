<?php

namespace App\Models;

use App\Enums\SubtitleStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Symfony\Component\Intl\Languages;

/**
 * @mixin IdeHelperSubtitle
 */
class Subtitle extends Model
{
    use SoftDeletes, HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => SubtitleStatus::class,
        'is_enabled' => 'boolean',
        'is_empty' => 'boolean',
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
        return Attribute::get(fn () => !is_null($this->file)
            ? route('video.subtitles', ['video' => $this->video, 'subtitle' => $this])
            : null
        );
    }

    protected function downloadUrl(): Attribute
    {
        return Attribute::get(fn () => !is_null($this->file)
            ? route('video.subtitles', ['video' => $this->video, 'subtitle' => $this, 'download' => true])
            : null
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: function($value) {

                if ($this->is_uploaded) {
                    return $value;
                }

                if ($this->language) {
                    return __(
                        ':language (auto-generated)',
                        ['language' => ucfirst(Languages::getName($this->language, $this->video->user->preferredLocale()))],
                        $this->video->user->preferredLocale()
                    );
                }

                return __('Auto-generated', locale: $this->video->user->preferredLocale());
            });
    }

    protected function fileName(): Attribute
    {
        return Attribute::get(fn () => !is_null($this->file)
            ? $this->name.'.vtt'
            : null
        );
    }

    protected function isPublic(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_enabled && in_array($this->status, [SubtitleStatus::GENERATED, SubtitleStatus::UPLOADED])
        );
    }

    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: fn () => in_array($this->status, [SubtitleStatus::GENERATED, SubtitleStatus::UPLOADED])
        );
    }

    protected function isUploaded(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === SubtitleStatus::UPLOADED
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
        return $query
            ->where('is_enabled', true)
            ->whereIn('status', [SubtitleStatus::GENERATED, SubtitleStatus::UPLOADED]);
    }
}
