<?php

namespace App\Http\Controllers\Api\Public;

use App\Events\SearchPerformed;
use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Meilisearch\Contracts\MultiSearchFederation;
use Meilisearch\Contracts\FederationOptions;
use Meilisearch\Client;
use Meilisearch\Contracts\SearchQuery;
use Illuminate\Support\Arr;

class SearchController extends Controller
{
    public function search (SearchRequest $request): JsonResponse {

        $q = $request->validated('q');

        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        $federation = (new MultiSearchFederation())->setLimit(10);

        $queries = [
            (new SearchQuery())
                ->setIndexUid((new Video())->searchableAs())
                ->setAttributesToRetrieve(['title', 'url', 'thumbnail', 'user', 'uuid', 'views', 'published_at', 'formated_duration'])
                ->setAttributesToHighlight(['title', 'user'])
                ->setAttributesToCrop(['title:15'])
                ->setSort(['views:desc', 'published_at:desc'])
                ->setQuery($q),
            (new SearchQuery())
                ->setIndexUid((new User)->searchableAs())
                ->setAttributesToRetrieve(['username', 'avatar', 'url', 'subscribers'])
                ->setAttributesToHighlight(['username'])
                ->setAttributesToCrop(['username:15'])
                ->setSort(['videos:desc', 'created_at:desc'])
                ->setQuery($q)
                ->setFederationOptions(
                    (new FederationOptions())->setWeight(1.2)
                )
        ];

        $results = $client->multiSearch($queries, $federation);

        SearchPerformed::dispatch($q, $results['estimatedTotalHits']);

        return response()->json([
            'total' => $results['estimatedTotalHits'],
            'items' => $this->format($results),
            'route' => route('search.index'). '?q=' .$q,
        ]);

    }

    private function format(array $results): array
    {
        return collect($results['hits'] ?? [])->map(function ($hit) {
            $indexUid = $hit['_federation']['indexUid'] ?? null;
            $cleanHit = Arr::except($hit, '_federation');

            return match ($indexUid) {
                (new Video())->searchableAs() => ['type' => 'video', ...$cleanHit],
                (new User())->searchableAs()  => ['type' => 'user', ...$cleanHit],
                default => null,
            };
        })->filter()->toArray();
    }
}
