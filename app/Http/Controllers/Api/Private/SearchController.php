<?php

namespace App\Http\Controllers\Api\Private;

use App\Enums\VideoStatus;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\User\UserSearchResource;
use App\Http\Resources\Video\VideoListResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class SearchController
{
    public function users(SearchRequest $request): ResourceCollection {

        $q = $request->string('q');

        $match = '%'.$q.'%';

        return UserSearchResource::collection(
            User::query()
                ->active()
                ->where(function (Builder $query) use ($match) {
                    $query->where('username', 'LIKE', $match)
                        ->orWhere('slug', 'LIKE', $match);
                })
                ->whereHas('comments', function (Builder $query) {
                    $query
                        ->whereHas('video', function (Builder $query) {
                            $query->where('user_id', Auth::id());
                        })
                        ->whereNull('parent_id');
                })
                ->limit(10)
                ->get()
        );
    }

    public function videos(SearchRequest $request): ResourceCollection {

        $q = $request->string('q');

        $match = '%'.$q.'%';

        return VideoListResource::collection(
            Video::query()
                ->where(
                    fn($q) => $q
                        ->active()
                        ->orWhere(fn($q) => $q
                            ->where('user_id' , Auth::user()->id)
                            ->whereNotNull('uploaded_at'))
                        ->whereNotIn('status', [VideoStatus::DRAFT, VideoStatus::BANNED, VideoStatus::FAILED])
                )
                ->where(
                    fn($query) => $query
                        ->where('title', 'LIKE', $match)
                        ->orWhere('description', 'LIKE', $match)
                        ->orWhereRelation('user', 'username', 'LIKE', $match)
                )
                ->with('user')
                ->latest('published_at')
                ->limit(24)
                ->get()
        );
    }
}
