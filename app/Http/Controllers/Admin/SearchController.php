<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\Search\UserSearchResource;
use App\Http\Resources\Search\VideoSearchResource;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SearchController
{
    public function users(Request $request): ResourceCollection {

        $q = $request->get('q');

        $match = '%'.$q.'%';

        return UserSearchResource::collection(
            User::query()
                ->where('username', 'LIKE', $match)
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
