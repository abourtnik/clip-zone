<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Resources\Video\VideoListResource;
use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryController
{
    public function videos(Category $category): ResourceCollection
    {
        return VideoListResource::collection(
            Video::active()
                ->whereRelation('category', 'id', $category->id)
                ->with('user')
                ->latest('publication_date')
                ->cursorPaginate(24)
        );
    }
}
