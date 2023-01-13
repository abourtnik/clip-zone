<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Interaction extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = ['id'];

    public $timestamps = false;

    protected static $recordEvents = ['created'];

    protected $casts = [
        'perform_at' => 'datetime',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likeable() : MorphTo
    {
        return $this->morphTo();
    }

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->properties = ['status' => $activity->subject->status];
    }

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults();
    }
}
