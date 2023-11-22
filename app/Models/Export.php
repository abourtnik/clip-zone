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

    public const EXPORT_FOLDER = 'exports';

    protected function isCompleted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === ExportStatus::COMPLETED
        );
    }

    protected function path(): Attribute
    {
        return Attribute::make(
            get: fn () => self::EXPORT_FOLDER.'/'.$this->file
        );
    }
}
