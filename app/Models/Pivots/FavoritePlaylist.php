<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperFavoritePlaylist
 */
class FavoritePlaylist extends Pivot
{
    protected $table = 'favorites_playlist';

    protected $casts = [
        'added_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public $timestamps = false;
}
