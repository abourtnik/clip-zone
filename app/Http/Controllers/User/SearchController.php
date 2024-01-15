<?php

namespace App\Http\Controllers\User;

use App\Enums\VideoStatus;
use App\Http\Resources\Search\UserSearchResource;
use App\Http\Resources\VideoResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class SearchController
{
    public function users(Request $request): ResourceCollection {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        return UserSearchResource::collection(
            User::query()
                ->where('username', 'LIKE', $match)
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

    public function videos(Request $request): ResourceCollection {

        list('q' => $q, 'except_ids' => $except_ids) = $request->only('q', 'except_ids');

        $match = '%'.$q.'%';

        return VideoResource::collection(
            Video::where(
                fn($q) => $q
                    ->active()
                    ->orWhere(fn($q) => $q
                        ->where('user_id' , Auth::user()->id)
                        ->whereNotNull('uploaded_at'))
                    ->whereNotIn('status', [VideoStatus::DRAFT, VideoStatus::BANNED, VideoStatus::FAILED])
            )
                ->whereNotIn('id', $except_ids)
                ->where(
                    fn($query) => $query
                        ->where('title', 'LIKE', $match)
                        ->orWhere('description', 'LIKE', $match)
                        ->orWhereRelation('user', 'username', 'LIKE', $match)
                )
                ->with('user')
                ->withCount('views')
                ->latest('publication_date')
                ->limit(24)
                ->get()
        );
    }
}
