<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @mixin IdeHelperActivity
 */
class Activity extends Model
{
    use Filterable;

    protected $table = 'activities';

    protected $guarded = ['id'];

    protected $casts = [
        'perform_at' => 'datetime'
    ];

    public $timestamps = false;

    /**
     * Get the parent subject model (comment or interaction).
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    protected function type(): Attribute
    {
        return Attribute::make(
            get: fn () => strtolower(class_basename($this->subject)) . ($this->subject->likeable ? '_'.strtolower(class_basename($this->subject->likeable)) : ''),
        );
    }

}
