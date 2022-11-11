<?php

namespace App\Models;

use App\Models\Interfaces\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\User
 *
 * @property boolean $is_admin
 */

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isAdministrator() : bool|null {
        return $this->is_admin;
    }

    public function videos() : HasMany {
        return $this->hasMany(Video::class);
    }

    public function getAvatarUrlAttribute() : string {
        return $this->avatar ? asset('storage/avatars/'. $this->avatar) : '/storage/images/default_men.png';
    }

    public function getBackgroundUrlAttribute() : string {
        return $this->avatar ?? '/storage/images/default_bg.jpg';
    }

    public function subscriptions() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'subscriber_id', 'user_id');
    }

    public function subscribers() : BelongsToMany {
        return $this->belongsToMany(User::class, 'subscriptions', 'user_id', 'subscriber_id');
    }

    public function isSubscribe (User $user) : bool {
        return $this->subscriptions()->where('user_id', $user->id)->exists();
    }

    public function getSubscribersCountAttribute () : int {
        return $this->subscribers()->count();
    }

    /*
    public function likes () : BelongsToMany {
        return $this->belongsToMany(User::class, 'likes', 'user_id', 'video_id')->wherePivot('status', true);
    }

    public function dislikes () : BelongsToMany {
        return $this->belongsToMany(User::class, 'likes', 'user_id', 'video_id')->wherePivot('status', false);
    }

    public function isLike (Video $video) : bool {
        return $this->likes()->where(['video_id' => $video->id])->exists();
    }

    public function isDislike (Video $video) : bool {
        return $this->dislikes()->where(['video_id' => $video->id])->exists();
    }
    */

    public function comments () : HasMany {
        return $this->hasMany(Comment::class);
    }

    public function likes() : HasMany
    {
        return $this->hasMany(Like::class);
    }

    public function like(Likeable $likeable): void
    {
        if ($this->hasLiked($likeable)) {
            $likeable->likes()
                ->whereHas('user', fn($q) => $q->whereId($this->id))
                ->delete();
        }

        else {
            Auth::user()->likes()->create([
                'likeable_type' => get_class($likeable),
                'likeable_id' => $likeable->id
            ]);
        }
    }

    public function hasLiked(Likeable $likeable): bool
    {
        return $likeable->likes()
            ->whereHas('user', fn($q) =>  $q->whereId($this->id))
            ->exists();
    }
}
