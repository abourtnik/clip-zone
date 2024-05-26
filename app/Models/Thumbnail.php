<?php

namespace App\Models;

use App\Enums\ThumbnailStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thumbnail extends Model
{
    use HasFactory;

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
            get: fn () => route('video.thumbnails', ['video' => $this->video, 'thumbnail' => $this])
        );
    }
}
