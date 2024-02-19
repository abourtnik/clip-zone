<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSearch
 */
class Search extends Model
{
    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'perform_at' => 'datetime',
    ];

    public function user () : BelongsTo {
        return $this->belongsTo(Video::class);
    }
}
