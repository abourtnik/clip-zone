<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum ReportReason : string {

    //'Sexual Content', 'Violent or repulsive content', 'Hateful or abusive content', 'Harassment or bullying', 'Harmful or dangerous acts', 'Misinformation', 'Child abuse', 'Promotes terrorism', 'Spam or misleading', 'Infringes my rights', 'Captions issue'

    case SEXUAL = 'Sexual Content';
    case VIOLENT = 'Violent or repulsive content';


    public static function get(): array {
        return Arr::pluck(self::cases(), 'value', 'name');
    }

    public static function valid(): array {
        return Arr::pluck(self::cases(), 'name');
    }
}
