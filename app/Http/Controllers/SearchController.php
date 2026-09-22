<?php

namespace App\Http\Controllers;

use App\Actions\Search\PerformSearch;
use App\Filters\SearchFilters;
use App\Http\Requests\SearchRequest;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(SearchRequest $request, SearchFilters $filters, PerformSearch $performSearch): View
    {
        return $performSearch->execute($request, $filters);
    }
}
