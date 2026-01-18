<?php

namespace App\Http\Controllers\Api\Public;

use App\Events\SearchPerformed;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Meilisearch\Endpoints\Indexes;

class SearchController extends Controller
{
    public function search (SearchRequest $request): JsonResponse {

        $q = $request->validated('q');

        $videos = Video::search($q, function(Indexes $index, $query, $options) {
            $options['attributesToRetrieve'] = ['title', 'url', 'thumbnail', 'user', 'uuid', 'views', 'published_at', 'formated_duration'];
            $options['attributesToHighlight'] = ['title', 'user'];
            $options['attributesToCrop'] = ['title:15'];
            return $index->rawSearch($query, $options);
        })
            ->orderBy('views', 'desc')
            ->latest('published_at')
            ->take(10)
            ->raw();

        SearchPerformed::dispatch($q, $videos['totalHits']);

        return response()->json([
            'total' => $videos['totalHits'],
            'items' => $videos['hits'],
            'route' => route('search.index'). '?q=' .$q,
        ]);

    }
}
