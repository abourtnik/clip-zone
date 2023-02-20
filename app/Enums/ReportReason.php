<?php

namespace App\Enums;

use Illuminate\Support\Arr;

enum ReportReason : string {

    case SEXUAL = 'Sexual Content';
    case VIOLENT = 'Violent or repulsive content';
    case ABUSIVE = 'Hateful or abusive content';
    case HARASSMENT = 'Harassment or bullying';
    case HARMFUL = 'Harmful or dangerous acts';
    case MISINFORMATION = 'Misinformation';
    case CHILD_ABUSE = 'Child abuse';
    case TERRORISM = 'Promotes terrorism';
    case INFRINGES = 'Infringes my rights';
    case CAPTIONS = 'Captions issue';


    public static function get(): array {
        return Arr::pluck(self::cases(), 'value', 'name');
    }

    public static function valid(): array {
        return Arr::pluck(self::cases(), 'value');
    }
}
