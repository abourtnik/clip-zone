<?php

namespace App\Enums;

use App\Enums\Traits\Listable;

enum DateRange: string
{
    use Listable;

    case ALL_TIME = 'all';
    case TODAY = 'today';
    case LAST_24_HOURS = '24h';
    case THIS_WEEK = 'week';
    case LAST_7_DAYS = '7d';
    case THIS_MONTH = 'month';
    case LAST_30_DAYS = '30d';
    case LAST_90_DAYS = '90d';
    case THIS_YEAR = 'year';
    case LAST_6_MONTHS = '6m';
    case LAST_12_MONTHS = '12m';
}
