<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperDevice
 */
class Device extends Model
{
    protected $table = 'personal_access_tokens';

    protected $guarded = ['id'];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public function user () : BelongsTo {
        return $this->belongsTo(User::class, 'tokenable_id');
    }
}
