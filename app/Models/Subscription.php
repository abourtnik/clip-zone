<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stripe\Subscription as StripeSubscription;

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
            get: fn () => (!$this->is_cancel || $this->ends_at?->isFuture()) && !$this->is_unpaid
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

    protected function isUnpaid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stripe_status === StripeSubscription::STATUS_PAST_DUE
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->stripe_status) {
                StripeSubscription::STATUS_ACTIVE => 'Active',
                StripeSubscription::STATUS_TRIALING => 'In trial period',
                StripeSubscription::STATUS_PAST_DUE => 'Unpaid',
                StripeSubscription::STATUS_CANCELED => 'Canceled',
            }
        );
    }

    /**
     * -------------------- SCOPES --------------------
     */

    /**
     * Scope a query to only include active subscriptions.
     *
     * @param  Builder $query
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $query->whereNull('ends_at')
            ->orWhere('ends_at', '>', Carbon::now());
    }
}
