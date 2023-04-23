<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FavoritePlaylist extends Pivot
{
    use HasFactory;

    protected $table = 'favorites_playlist';

    protected $casts = [
        'added_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public $timestamps = false;
}
