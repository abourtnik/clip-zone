<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Resources\User\UserSearchResource;
use App\Http\Resources\Video\VideoSearchResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchController
{
    public function users(Request $request): ResourceCollection {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        return UserSearchResource::collection(
            User::query()
                ->where(function (Builder $query) use ($match) {
                    $query->where('username', 'LIKE', $match)
                        ->orWhere('slug', 'LIKE', $match);
                })
                ->limit(10)
                ->get()
        );
    }

    public function videos(Request $request): ResourceCollection {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        return VideoSearchResource::collection(
            Video::query()
                ->where('title', 'LIKE', $match)
                ->limit(10)
                ->get()
        );
    }
}
