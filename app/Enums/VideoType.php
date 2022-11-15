<?php

namespace App\Enums;

enum VideoType : string {

    case MP4 = 'video/mp4';
    case MOV = 'video/quicktime';
    case OGG = 'video/ogg';
    case WEBM = 'video/webm';

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
