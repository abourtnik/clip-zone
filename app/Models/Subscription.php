<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $table = 'premium_subscriptions';

    protected $casts = [
        'next_payment' => 'datetime',
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan() : BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => !$this->is_cancel || $this->ends_at?->isFuture()
        );
    }

    protected function onTrial(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->trial_ends_at?->isFuture()
        );
    }

    protected function isCancel(): Attribute
    {
        return Attribute::make(
            get: fn () => !is_null($this->ends_at)
        );
    }
}
