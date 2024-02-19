<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\HasActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @mixin IdeHelperInteraction
 */
class Interaction extends Model
{
    use HasFactory, Filterable, HasActivity;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $casts = [
        'perform_at' => 'datetime',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable() : MorphTo
    {
        return $this->morphTo();
    }
}
