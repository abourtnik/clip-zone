<?php

namespace App\Models;

use App\Enums\VideoStatus;
use App\Models\Interfaces\Likeable;
use App\Models\Interfaces\Reportable;
use App\Models\Traits\HasReport;
use App\Notifications\PasswordUpdate;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Lab404\Impersonate\Models\Impersonate;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Symfony\Component\Intl\Countries;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;

/**
 * App\Models\User
 *
 * @property boolean $is_admin
 */

class User extends Authenticatable implements MustVerifyEmail, Reportable
{
    use HasFactory, Notifiable, HasRelationships, HasReport, Impersonate;

    protected $guarded = ['id', 'is_admin'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_at' => 'datetime',
        'last_login_at' => 'datetime'
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
        return $this->belongsToMany(User::class, 'subscriptions', 'subscriber_id', 'user_id')
            ->using(Subscription::class)
            ->withPivot(['subscribe_at', 'read_at']);
    }

    public function subscribers() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'user_id', 'subscriber_id')
            ->using(Subscription::class)
            ->withPivot(['subscribe_at', 'read_at']);
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

    public function videos_comments_interactions () : HasManyDeep {

        return $this->hasManyDeep(Interaction::class,
            [Video::class, Comment::class],
            ['user_id', 'video_id', 'likeable_id']
        );
    }

    public function interactions () : HasMany {
        return $this->hasMany(Interaction::class);
    }

    public function likes () : HasMany {
        return $this->hasMany(Interaction::class)->where('status', true);
    }

    public function dislikes () : HasMany {
        return $this->hasMany(Interaction::class)->where('status', false);
    }

    public function pinned_video () : HasOne {
        return $this->hasOne(Video::class, 'id', 'pinned_video_id')->where('status', VideoStatus::PUBLIC);
    }

    public function activity () : HasMany {
        return $this->hasMany(Activity::class, 'causer_id');
    }

    public function user_reports () : HasMany {
        return $this->hasMany(Report::class, 'user_id');
    }

    public function video_reports () : HasManyThrough {
        return $this->hasManyThrough(Report::class, Video::class, 'user_id', 'reportable_id')
            ->where('reportable_type', Video::class);
    }

    public function playlists () : HasMany {
        return $this->hasMany(Playlist::class);
    }

    public function favorites_playlist () : BelongsToMany {
        return $this->belongsToMany(Playlist::class, 'favorites_playlist', 'user_id', 'playlist_id')
            ->using(FavoritePlaylist::class)
            ->withPivot(['added_at']);
    }

    public function notifications () : HasMany {
        return $this->hasMany(Notification::class);
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
            get: fn () => route('user.show', $this)
        );
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hasVerifiedEmail()
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

    protected function shortDescription(): Attribute
    {
        return Attribute::make(
            get: fn () => Str::limit($this->description, '300', '...')
        );
    }

    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => !is_null($value)
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
        $query->whereNotNull(['email_verified_at'])->whereNull(['banned_at']);
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

    public function report(Reportable $reportable): Report|null
    {
        return $reportable->reports()
            ->whereRelation('user', 'id', $this->id)
            ->first();
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
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token) : void
    {
        $this->notify(new ResetPassword($token));
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

    /**
     * Update User Password
     *
     * @param string $password
     * @return void
     */
    public function updatePassword(string $password): void
    {
        $this->update([
            'password' => $password
        ]);

        $this->setRememberToken(Str::random(60));

        $this->notify(new PasswordUpdate());
    }

    /**
     * @return bool
     */
    public function canImpersonate() : bool
    {
        return $this->is_admin;
    }

    /**
     * @return bool
     */
    public function canBeImpersonated()
    {
        return !$this->is_admin;
    }
}
