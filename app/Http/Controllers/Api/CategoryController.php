<?php

namespace App\Http\Controllers\Api;

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
                ->withCount('views')
                ->latest('publication_date')
                ->paginate(24)
        );
    }
}
