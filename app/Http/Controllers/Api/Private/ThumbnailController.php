<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Controllers\Controller;
use App\Http\Resources\ThumbnailResource;
use App\Models\Video;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ThumbnailController extends Controller
{
    public function index(Video $video): ResourceCollection
    {
        return (
            ThumbnailResource::collection($video->thumbnails)
        );
    }
}
