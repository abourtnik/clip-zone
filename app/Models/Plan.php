<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPlan
 */
class Plan extends Model
{
    protected $guarded = ['id'];

    public function period() : Attribute {
        return Attribute::make(
            get: fn () => $this->duration === 1 ? 'month' : 'year'
        );
    }
}
