<?php

namespace App\Models;

use App\Casts\MoneyCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperPlan
 */
class Plan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'price' => MoneyCast::class.':0',
    ];

    public function period() : Attribute {
        return Attribute::make(
            get: fn () => $this->duration === 1 ? 'month' : 'year'
        );
    }
}
