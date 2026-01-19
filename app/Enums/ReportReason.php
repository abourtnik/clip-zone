<?php

namespace App\Enums;

use App\Enums\Traits\Listable;

enum ReportReason : int {

    use Listable;

    case SEXUAL_CONTENT = 0;
    case VIOLENT_OR_REPULSIVE_CONTENT = 1;
    case HATEFUL_OR_ABUSIVE_CONTENT = 2;
    case HARASSMENT_OR_BULLYING = 3;
    case HARMFUL_OR_DANGEROUS_ACTS = 4;
    case MISINFORMATION = 5;
    case CHILD_ABUSE = 6;
    case PROMOTES_TERRORISM = 7;
    case INFRINGES_MY_RIGHTS = 8;
    case CAPTIONS_ISSUE = 9;
}
