<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function categories(): Response
    {
        return $this->renderSitemap('categories', function () {
            return Category::query()
                ->where('in_menu', true)
                ->ordered()
                ->withMax('videos', 'published_at')
                ->get();
        });
    }

    public function videos(): Response
    {
        return $this->renderSitemap('videos', function () {
            return Video::query()->select(['id', 'uuid', 'slug', 'duration', 'title', 'description', 'file', 'published_at'])
                ->active()
                ->latest('published_at')
                ->get();
        });
    }

    public function users(): Response
    {
        return $this->renderSitemap('users', function () {
            return User::query()->select(['id', 'slug'])
                ->active()
                ->latest('created_at')
                ->whereHas('videos', function (Builder $query) {
                    $query->active();
                })
                ->withMax(['videos' => function (Builder $query) {
                    $query->active();
                }], 'published_at')
                ->get();
        });
    }

    private function renderSitemap(string $key, callable $dataResolver, int $ttlHours = 1): Response
    {
        $cacheKey = "sitemap-{$key}";
        $view = "sitemaps.{$key}";

        $content = Cache::remember($cacheKey, now()->addHours($ttlHours), function () use ($view, $dataResolver, $key) {
            return view($view, [$key => $dataResolver()])->render();
        });

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

}
