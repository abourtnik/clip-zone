<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Subscription extends Pivot
{
    use HasFactory;

    protected $table = 'subscriptions';

    protected $casts = [
        'subscribe_at' => 'datetime',
    ];

    protected $guarded = ['id'];

    public $timestamps = false;
}
