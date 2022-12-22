<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use App\Notifications\VerifyEmail;
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
    ];

    public function isAdministrator() : bool|null {
        return $this->is_admin;
    }

    /**
     * Relations
     */

    public function videos() : HasMany {
        return $this->hasMany(Video::class);
    }

    public function views() : HasMany {
        return $this->hasMany(View::class);
    }

    public function subscriptions() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'subscriber_id', 'user_id');
    }

    public function latest_subscriptions() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'subscriber_id', 'user_id')->latest('subscribe_at');
    }

    public function subscribers() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'user_id', 'subscriber_id')
            ->using(Subscription::class)
            ->withPivot('subscribe_at');
    }

    public function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function videos_comments () : HasManyThrough {
        return $this->hasManyThrough(Comment::class, Video::class);
    }

    public function videos_views () : HasManyThrough {
        return $this->hasManyThrough(View::class, Video::class);
    }

    public function videos_interactions (): HasManyThrough {
        return $this->hasManyThrough(Like::class, Video::class, 'user_id', 'likeable_id')
            ->where('likeable_type', Video::class);
    }

    public function videos_likes (): HasManyThrough {
        return $this->hasManyThrough(Like::class, Video::class, 'user_id', 'likeable_id')
            ->where('likes.status', true)
            ->where('likeable_type', Video::class);
    }

    public function videos_dislikes (): HasManyThrough {
        return $this->hasManyThrough(Like::class, Video::class, 'user_id', 'likeable_id')
            ->where('likes.status', false)
            ->where('likeable_type', Video::class);
    }

    public function isSubscribe (User $user) : bool {
        return $this->subscriptions()->where('user_id', $user->id)->exists();
    }

    public function getAvatarUrlAttribute() : string {
        return $this->avatar ? asset('storage/avatars/'. $this->avatar) : '/images/default_men.png';
    }

    public function getBackgroundUrlAttribute() : string {
        return $this->banner ? asset('storage/banners/'. $this->banner) : '/images/default_banner.jpg';
    }

    public function likes () : HasMany {
        return $this->hasMany(Like::class);
    }

    public function hasInteract(Likeable $likeable): bool
    {
        return $likeable->interactions()
            ->whereHas('user', fn($q) =>  $q->whereId($this->id))
            ->exists();
    }

    public function hasLiked(Likeable $likeable): bool
    {
        return $likeable->likes()
            ->whereHas('user', fn($q) =>  $q->whereId($this->id))
            ->where('status', true)
            ->exists();
    }

    public function hasDisliked(Likeable $likeable): bool
    {
        return $likeable->dislikes()
            ->whereHas('user', fn($q) =>  $q->whereId($this->id))
            ->where('status', false)
            ->exists();
    }

    /**
     * Attributes
     */

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

    /*public function views () : Attribute {
        return Attribute::make(
            get: fn () => number_format(num: $this->videos()->sum('views'), thousands_separator: ' ')
        );
    }*/

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
