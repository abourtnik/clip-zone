<?php

namespace App\Models;

use App\Helpers\Number;
use App\Models\Interfaces\Likeable;
use App\Models\Interfaces\Reportable;
use App\Models\Pivots\FavoritePlaylist;
use App\Models\Pivots\Subscription;
use App\Models\Subscription as PremiumSubscription;
use App\Models\Traits\Filterable;
use App\Models\Traits\HasReport;
use App\Models\Traits\MustVerifyPhone;
use App\Models\Traits\MustVerifyUpdatedEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Symfony\Component\Intl\Countries;

/**
 * @mixin IdeHelperUser
 */

class User extends Authenticatable implements MustVerifyEmail, Reportable
{
    use HasFactory, Notifiable, HasRelationships, HasReport, Impersonate, Billable, Filterable, HasApiTokens, MustVerifyUpdatedEmail, MustVerifyPhone, CanResetPassword, SoftDeletes;

    protected $guarded = ['id', 'is_admin'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'show_subscribers' => 'boolean',
        'banned_at' => 'datetime',
        'last_login_at' => 'datetime',
        'phone' => E164PhoneNumberCast::class,
        'phone_verified_at' => 'datetime'
    ];

    public const string AVATAR_FOLDER = 'avatars';
    public const string DEFAULT_AVATAR = '/images/default_men.png';
    public const string BANNER_FOLDER = 'banners';
    public const string DEFAULT_BANNER = '/images/default_banner.jpg';

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
        return $this->hasOne(Video::class, 'id', 'pinned_video_id')
            ->active();
    }

    public function activity () : HasMany {
        return $this->hasMany(Activity::class);
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

    public function premium_subscription () : HasOne {
        return $this->hasOne(PremiumSubscription::class);
    }

    public function devices () : HasMany {
        return $this->hasMany(Device::class, 'tokenable_id');
    }

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    public function avatarUrl() : Attribute {
        return Attribute::make(
            get: fn () => route('user.avatar', $this)
        );
    }

    public function bannerUrl() : Attribute {
        return Attribute::make(
            get: fn () => route('user.banner', $this)
        );
    }

    public function firstActiveVideo (): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos()->active()->latest('published_at')->first()
        );
    }

    public function othersActiveVideos (): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos()->whereNot('id', $this->first_active_video->id)->active()->latest('published_at')
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
            get: fn ($value) => $this->country ? Countries::getName($this->country, app()->getLocale()) : null,
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
            get: fn () => $this->hasVerifiedEmail() && !$this->is_banned
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

    protected function isPremium(): Attribute
    {
        return Attribute::make(
            get: fn () => !is_null($this->premium_subscription) && $this->premium_subscription->is_active
        );
    }

    protected function hasCurrentSubscription(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_premium || ($this->premium_subscription && $this->premium_subscription->is_unpaid)
        );
    }

    protected function plan(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->is_premium ? 'premium' : 'free'
        );
    }

    protected function uploadedVideos(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos()
                ->valid()
                ->count()
        );
    }

    protected function uploadedVideosSize(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->videos()
                ->valid()
                ->sum('size')
        );
    }

    protected function json(): Attribute
    {
        return Attribute::make(
            get: fn () => collect([
                'id' => $this->id,
                'avatar' => $this->avatar_url,
                'is_premium' => $this->is_premium,
            ])->toJson()
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
     * @return bool
     */
    public function canImpersonate() : bool
    {
        return $this->is_admin;
    }

    /**
     * @return bool
     */
    public function canBeImpersonated(): bool
    {
        return !$this->is_admin;
    }

    /**
     * Get the customer name that should be synced to Stripe.
     */
    public function stripeName(): string
    {
        return $this->username;
    }

    /**
     * Get the customer name that should be synced to Stripe.
     */
    public function stripePhone(): ?string
    {
        return $this->hasVerifiedPhone() ? $this->phone : null;
    }

    public static function generateSlug(string $username) : string {

        $slug = Str::ucfirst(Str::camel(Str::slug($username)));

        // Str::slug() generate empty string with non latin character
        if (empty($slug)) {
            return 'user-' .Number::unique();
        }

        if (User::query()->where('slug', $slug)->exists()){
            return $slug. '-' .Number::unique();
        }

        return $slug;
    }
}
