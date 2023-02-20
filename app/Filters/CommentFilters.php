<?php

namespace App\Filters;

use App\Filters\Comments\AuthorFilter;
use App\Filters\Comments\BanFilter;
use App\Filters\Comments\DateFilter;
use App\Filters\Comments\SearchFilter;
use App\Filters\Comments\VideoFilter;
use App\Filters\Comments\RepliesFilter;

class CommentFilters extends AbstractFilters
{
    protected array $filters = [
        'search' => SearchFilter::class,
        'video' => VideoFilter::class,
        'user' => AuthorFilter::class,
        'date' => DateFilter::class,
        'replies' => RepliesFilter::class,
        'ban' => BanFilter::class,
    ];
}
