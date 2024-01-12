<?php

namespace App\Models;

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
        'card_expired_at' => 'date',
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
            get: fn () => !$this->is_canceled && !$this->is_unpaid
        );
    }

    protected function onTrial(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->trial_ends_at?->isFuture()
        );
    }

    protected function trialCanceled(): Attribute
    {
        return Attribute::make(
            get: fn () => !is_null($this->ends_at)
        );
    }

    protected function isCanceled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stripe_status === StripeSubscription::STATUS_CANCELED
        );
    }

    protected function isUnpaid(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stripe_status === StripeSubscription::STATUS_UNPAID
        );
    }

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->stripe_status) {
                StripeSubscription::STATUS_ACTIVE => 'Active',
                StripeSubscription::STATUS_TRIALING => 'In trial period',
                StripeSubscription::STATUS_CANCELED => 'Canceled',
                StripeSubscription::STATUS_UNPAID => 'Unpaid',
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
        $query->whereNotIn('stripe_status', [StripeSubscription::STATUS_UNPAID, StripeSubscription::STATUS_CANCELED]);
    }
}
