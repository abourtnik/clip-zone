<?php

namespace App\Listeners;

use App\Events\SearchPerformed;
use App\Models\Search;
use Illuminate\Support\Facades\Auth;

class SearchEventSubscriber
{
    /**
     * Handle user perform search.
     */
    public function handleSearchPerformed(SearchPerformed $event): void {
        if (!Auth::user()?->is_admin) {
            Search::query()->create([
                'user_id' => Auth::user()?->id,
                'query' => $event->query,
                'ip' => request()->ip(),
                'lang' => request()->getPreferredLanguage(),
                'results' => $event->results
            ]);
        }
    }
}
