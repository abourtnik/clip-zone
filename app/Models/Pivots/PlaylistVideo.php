<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PlaylistVideo extends Pivot
{
    use HasFactory;

    protected $table = 'playlist_has_videos';

    protected $guarded = ['id'];

    public $timestamps = false;
}
