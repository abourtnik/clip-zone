<?php

namespace App\Http\Controllers;

use App\Filters\SearchFilters;
use App\Http\Requests\SearchRequest;
use App\Models\Video;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use Meilisearch\Endpoints\Indexes;

class SearchController extends Controller
{
    public function index(SearchRequest $request, SearchFilters $filters): View
    {
        $q = $request->validated('q');

        if (!$q) {
            return view('pages.search', [
                'search' => $q,
                'results' => collect()
            ]);
        }

        $videos = Video::search($q, function(Indexes $index, $query, $options) use ($filters){
                $options['filter'] = $filters->apply();
                return $index->rawSearch($query, $options);
            })
            ->query(function (Builder $query){
                return $query->with('user');
            })
            ->orderBy('views', 'desc')
            ->orderBy('publication_date', 'desc')
            ->paginate(12)
            ->withQueryString();


        return view('pages.search', [
            'search' => $q,
            'results' => $videos,
            'filters' => $filters->receivedFilters()
        ]);
    }
}
