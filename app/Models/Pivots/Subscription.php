<?php

namespace App\Models\Pivots;

use App\Models\Traits\Filterable;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin IdeHelperSubscription
 */
class Subscription extends Pivot
{
    use HasFactory, Filterable;

    protected $table = 'subscriptions';

    protected $casts = [
        'subscribe_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public $timestamps = false;

    public function subscriber () : HasOne {
        return $this->hasOne(User::class, 'id', 'subscriber_id');
    }

    public function user () : HasOne {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
