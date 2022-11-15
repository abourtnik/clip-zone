<?php

namespace App\Enums;

enum ThumbnailType : string {

    case PNG = 'image/png';
    case JPEG = 'image/jpeg';
    case WEBP = 'image/webp';

    public static function get(): array {
        return array_map(fn($a): array => ['id' => $a->value, 'name' => ucfirst(strtolower(($a->name)))], self::cases());
    }

    public static function acceptedFormats(): array {
        return array_column(self::cases(), 'name');
    }

    public static function acceptedMimeTypes(): array {
        return array_column(self::cases(), 'value');
    }
}
