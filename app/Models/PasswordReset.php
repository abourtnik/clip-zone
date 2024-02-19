<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperPasswordReset
 */
class PasswordReset extends Model
{
    protected $table = 'password_resets';

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function IsExpired(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->created_at->addMinutes(60)->lte(now())
        );
    }
}
