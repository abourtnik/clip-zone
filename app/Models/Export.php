<?php

namespace App\Models;

use App\Enums\ExportStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Export extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => ExportStatus::class,
    ];

    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === ExportStatus::COMPLETED
        );
    }
}
