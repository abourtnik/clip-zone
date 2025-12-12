<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @mixin IdeHelperView
 */
class View extends Model
{
    use HasFactory, Filterable, SoftDeletes;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'view_at' => 'datetime',
    ];

    public function video (): BelongsTo {
        return $this->belongsTo(Video::class);
    }

    public function user (): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
