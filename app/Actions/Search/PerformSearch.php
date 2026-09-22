<?php

namespace App\Actions\Search;

use App\Filters\SearchFilters;
use App\Http\Requests\SearchRequest;
use App\Models\User;
use App\Models\Video;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\View;
use Meilisearch\Client;
use Meilisearch\Contracts\FederationOptions;
use Meilisearch\Contracts\MultiSearchFederation;
use Meilisearch\Contracts\SearchQuery;

class PerformSearch
{
    const int PER_PAGE = 12;

    private ?string $q;
    private SearchFilters $filters;
    private SearchRequest $searchRequest;

    public function execute(SearchRequest $searchRequest, SearchFilters $filters): View
    {
        $this->searchRequest = $searchRequest;
        $this->filters = $filters;
        $this->q = $searchRequest->validated('q');

        if (!$this->q) {
            return view('pages.search', [
                'search' => $this->q,
                'results' => collect()
            ]);
        }

        return view('pages.search', [
            'search' => $this->q,
            'results' => $this->getResults(),
            'filters' => $filters->receivedFilters()
        ]);
    }

    private function getResults(): LengthAwarePaginator
    {
        $client = new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));

        $page = $this->searchRequest->integer('page', 1);

        $offset = ($page - 1) * self::PER_PAGE;

        $federation = (new MultiSearchFederation())
            ->setLimit(self::PER_PAGE)
            ->setOffset($offset);

        $response = $client->multiSearch($this->getQueries(), $federation);

        $items = $this->hydrateModels(collect($response['hits']));

        return new LengthAwarePaginator(
            $items,
            $response['estimatedTotalHits'],
            self::PER_PAGE,
            $page,
            [
                'path' => route('search.index'),
                'query' => $this->searchRequest->query(),
            ]
        );
    }

    private function getQueries(): array
    {
        $type = $this->filters->receivedFilters()['type'] ?? null;
        $resources = $this->resources();

        if ($type !== null && isset($resources[$type])) {
            return [$resources[$type]['query']];
        }

        return collect($resources)
            ->map(fn (array $resource) => $resource['query'])
            ->values()
            ->all();
    }

    private function hydrateModels(Collection $hits): Collection
    {
        $resources = $this->resources();

        $indexToResource = collect($resources)
            ->mapWithKeys(fn (array $resource, string $key) => [
                (new $resource['model'])->searchableAs() => $key,
            ]);

        $idsByResource = $hits
            ->groupBy(fn ($hit) => $indexToResource->get($hit['_federation']['indexUid'] ?? null))
            ->map(fn (Collection $group) => $group->pluck('id')->all());


        $modelsByResource = $idsByResource->map(
            fn (array $ids, string $resourceKey) => $resources[$resourceKey]['hydrate']($ids)->keyBy('id')
        );

        return $hits->map(function ($hit) use ($indexToResource, $modelsByResource, $resources) {
            $resourceKey = $indexToResource->get($hit['_federation']['indexUid'] ?? null);

            $model = $modelsByResource->get($resourceKey)?->get($hit['id']);

            foreach ($resources[$resourceKey]['highlight_attributes'] as $attribute) {
                $model->setAttribute("formatted_$attribute", $hit['_formatted'][$attribute] ?? null);
            }

            return $model;
        })->filter()->values();
    }

    private function resources(): array
    {
        return [
            'video' => [
                'model' => Video::class,
                'query' => (new SearchQuery())
                    ->setIndexUid((new Video())->searchableAs())
                    ->setAttributesToRetrieve(['id'])
                    ->setAttributesToHighlight(['title', 'description', 'user'])
                    ->setAttributesToCrop(['description:50'])
                    ->setSort(['views:desc', 'published_at:desc'])
                    ->setFilter($this->filters->apply())
                    ->setQuery($this->q),
                'hydrate' => fn (array $ids): EloquentCollection => Video::query()
                    ->whereIn('id', $ids)
                    ->with('user')
                    ->get(),
                'highlight_attributes' => ['title', 'description', 'user'],
            ],
            'user' => [
                'model' => User::class,
                'query' => (new SearchQuery())
                    ->setIndexUid((new User())->searchableAs())
                    ->setAttributesToRetrieve(['id'])
                    ->setAttributesToHighlight(['username','description', 'website', 'slug'])
                    ->setAttributesToCrop(['description:50'])
                    ->setSort(['videos:desc', 'created_at:desc'])
                    ->setQuery($this->q)
                    ->setFederationOptions((new FederationOptions())->setWeight(1.2)),
                'hydrate' => fn (array $ids): EloquentCollection => User::query()
                    ->whereIn('id', $ids)
                    ->withCount(['videos', 'subscribers'])
                    ->get(),
                'highlight_attributes' => ['username', 'description', 'website', 'slug'],
            ],
        ];
    }
}
