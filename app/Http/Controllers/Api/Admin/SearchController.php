<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\User\UserSearchResource;
use App\Http\Resources\Video\VideoSearchResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchController
{
    public function users(SearchRequest $request): ResourceCollection {

        $q = $request->validated('q');

        return UserSearchResource::collection(
            User::query()
                ->whereAny(['username', 'slug'], 'LIKE', '%'.$q.'%')
                ->limit(10)
                ->get()
        );
    }

    public function videos(Request $request): ResourceCollection {

        $q = $request->string('q');

        return VideoSearchResource::collection(
            Video::query()
                ->whereAny(['title'], 'LIKE','%'.$q.'%')
                ->limit(10)
                ->get()
        );
    }
}
