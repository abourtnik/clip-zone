<?php

namespace App\Models;

use App\Enums\ThumbnailStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thumbnail extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'status' => ThumbnailStatus::class,
    ];

    public function video() : BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => route('video.thumbnail', ['video' => $this->video, 'thumbnail' => $this])
        );
    }
}
