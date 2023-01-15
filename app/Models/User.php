<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\Intl\Countries;

/**
 * App\Models\User
 *
 * @property boolean $is_admin
 */

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_at' => 'datetime'
    ];

    /**
     * -------------------- RELATIONS --------------------
     */

    public function videos() : HasMany {
        return $this->hasMany(Video::class);
    }

    public function views() : HasMany {
        return $this->hasMany(View::class);
    }

    public function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function subscriptions() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'subscriber_id', 'user_id');
    }

    public function subscribers() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'user_id', 'subscriber_id')
            ->using(Subscription::class)
            ->withPivot('subscribe_at');
    }

    public function subscriptions_videos () : HasManyThrough {
        return $this->hasManyThrough(Video::class, Subscription::class, 'subscriber_id', 'user_id', 'id', 'user_id');
    }

    public function videos_comments () : HasManyThrough {
        return $this->hasManyThrough(Comment::class, Video::class);
    }

    public function videos_views () : HasManyThrough {
        return $this->hasManyThrough(View::class, Video::class);
    }

    public function videos_interactions (): HasManyThrough {
        return $this->hasManyThrough(Interaction::class, Video::class, 'user_id', 'likeable_id')
            ->where('likeable_type', Video::class);
    }

    public function videos_likes (): HasManyThrough {
        return $this->hasManyThrough(Interaction::class, Video::class, 'user_id', 'likeable_id')
            ->where('interactions.status', true)
            ->where('likeable_type', Video::class);
    }

    public function videos_dislikes (): HasManyThrough {
        return $this->hasManyThrough(Interaction::class, Video::class, 'user_id', 'likeable_id')
            ->where('interactions.status', false)
            ->where('likeable_type', Video::class);
    }

    public function interactions () : HasMany {
        return $this->hasMany(Interaction::class);
    }

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    public function avatarUrl() : Attribute {
        return Attribute::make(
            get: fn () => $this->avatar ? asset('storage/avatars/'. $this->avatar) : '/images/default_men.png'
        );
    }

    public function bannerUrl() : Attribute {
        return Attribute::make(
            get: fn () => $this->banner ? asset('storage/banners/'. $this->banner) : '/images/default_banner.jpg'
        );
    }

    public function firstActiveVideo (): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos()->active()->latest('publication_date')->first()
        );
    }

    public function othersActiveVideos (): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos()->whereNot('id', $this->first_active_video->id)->active()->latest('publication_date')
        );
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value),
        );
    }

    protected function countryName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Countries::getName($this->country),
        );
    }

    protected function route(): Attribute
    {
        return Attribute::make(
            get: fn () => route('pages.user', $this)
        );
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => !$this->is_banned && $this->hasVerifiedEmail() && !$this->is_admin
        );
    }

    protected function isBanned(): Attribute
    {
        return Attribute::make(
            get: fn () => !is_null($this->banned_at)
        );
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => 'user',
        );
    }

    /**
     * -------------------- SCOPES --------------------
     */

    /**
     * Scope a query to only include active users.
     *
     * @param  Builder $query
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $query->whereNotNull(['email_verified_at',])->whereNull(['is_admin', 'banned_at']);
    }

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }

    /**
     * -------------------- METHODS --------------------
     */

    public function isSubscribeTo(User $user) : bool
    {
        return $this->subscriptions()->where('user_id', $user->id)->exists();
    }

    public function hasLiked(Likeable $likeable): bool
    {
        return $likeable->likes()
            ->whereRelation('user', 'id', $this->id)
            ->exists();
    }

    public function hasDisliked(Likeable $likeable): bool
    {
        return $likeable->dislikes()
            ->whereRelation('user', 'id', $this->id)
            ->exists();
    }

    /**
     * Send e-mail for verify account.
     *
     * @return void
     */
    public function sendEmailVerificationNotification () : void {
        $this->notify(new VerifyEmail);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
            'confirmation_token' => null,
        ])->save();
    }
}
