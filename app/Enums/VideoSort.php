<?php


namespace App\Enums;

enum VideoSort: string
{
    case LATEST = 'latest';
    case POPULAR = 'popular';
    case OLDEST = 'oldest';
}
