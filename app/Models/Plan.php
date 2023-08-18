<?php

namespace App\Models;

use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function period() : Attribute {
        return Attribute::make(
            get: fn () => CarbonInterval::days($this->duration)->forHumans(['join' => fn($a) => explode(' ', $a[0])[1]])
        );
    }
}
