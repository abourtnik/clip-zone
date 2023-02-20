<?php

namespace App\Models;

use App\Enums\ReportReason;
use App\Enums\ReportStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'reason' => ReportReason::class,
        'status' => ReportStatus::class,
        'first_report_at' => 'datetime'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reportable() : MorphTo
    {
        return $this->morphTo();
    }

    /**
     * -------------------- ATTRIBUTES --------------------
     */

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => ucfirst(strtolower(class_basename($this->reportable_type)))
        );
    }

    protected function isPending(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === ReportStatus::PENDING
        );
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->type) {
                'User' => 'user',
                'Comment' => 'comment',
                'Video' => 'video',
            }
        );
    }

    /**
     * -------------------- SCOPES --------------------
     */

    public function scopeFilter(Builder $query, $filters)
    {
        return $filters->apply($query);
    }
}
