<?php

namespace App\Http\Controllers\Api;

use App\Events\SearchPerformed;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Meilisearch\Endpoints\Indexes;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    public function search (Request $request): JsonResponse {

        $q = $request->get('q');

        SearchPerformed::dispatch($q);

        $videos = Video::search($q, function(Indexes $index, $query, $options) {
            $options['attributesToRetrieve'] = ['title', 'url', 'thumbnail', 'user', 'uuid', 'views', 'publication_date', 'formated_duration'];
            $options['attributesToHighlight'] = ['title', 'user'];
            $options['attributesToCrop'] = ['title:15'];
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
