<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stripe\Subscription as StripeSubscription;

/**
 * @mixin IdeHelperSubscription
 */
class Subscription extends Model
{
    protected $guarded = ['id'];

    protected $table = 'premiums';

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

    protected function isTrialCanceled(): Attribute
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
                StripeSubscription::STATUS_ACTIVE => __('Active'),
                StripeSubscription::STATUS_TRIALING => __('In trial period'),
                StripeSubscription::STATUS_CANCELED => __('Canceled'),
                StripeSubscription::STATUS_UNPAID => __('Unpaid'),
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
