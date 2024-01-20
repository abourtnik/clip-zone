<?php

namespace App\Http\Controllers;

use App\Filters\SearchFilters;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Database\Eloquent\Builder;
use MeiliSearch\Endpoints\Indexes;

class SearchController extends Controller
{
    public function index(Request $request, SearchFilters $filters): View
    {
        $q = $request->get('q');

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
                return $query
                    ->with('user')
                    ->withCount('views');
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

    public function search (Request $request): JsonResponse {

        $q = $request->get('q');

        $videos = Video::search($q, function(Indexes $index, $query, $options) {
                $options['attributesToRetrieve'] = ['title', 'url', 'thumbnail', 'user'];
                $options['attributesToHighlight'] = ['title', 'user'];
                $options['attributesToCrop'] = ['title:10'];
                return $index->rawSearch($query, $options);
            })
            ->orderBy('views', 'desc')
            ->orderBy('publication_date', 'desc')
            ->take(10)
            ->raw();

        return response()->json([
            'total' => $videos['totalHits'],
            'items' => $videos['hits'],
            'route' => route('search.index'). '?q=' .$q,
        ]);

    }
}
