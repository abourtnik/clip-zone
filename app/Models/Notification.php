<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperNotification
 */
class Notification extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     * -------------------- ATTRIBUTES --------------------
     */

    public function isRead() : Attribute {
        return Attribute::make(
            get: fn () => !is_null($this->read_at)
        );
    }


    /**
     * -------------------- SCOPES --------------------
     */

    /**
     * Scope a query to only include not read notifications.
     *
     * @param  Builder $query
     * @return void
     */
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }
}
