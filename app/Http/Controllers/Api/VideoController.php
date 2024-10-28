<?php

namespace App\Http\Controllers\Api;

use App\Enums\ThumbnailStatus;
use App\Enums\VideoStatus;
use App\Helpers\Number;
use App\Http\Requests\Video\FileRequest;
use App\Http\Resources\Video\VideoListResource;
use App\Http\Resources\Video\VideoShowResource;
use App\Jobs\BuildFullFileFromChunks;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VideoController
{
    public function index(): ResourceCollection
    {
        return VideoListResource::collection(
            Video::active()
                ->with('user')
                ->withCount('views')
                ->latest('publication_date')
                ->paginate(24)
        );
    }

    public function trend(): ResourceCollection
    {
        return VideoListResource::collection(
            Video::active()
                ->with('user')
                ->withCount('views')
                ->orderBy('views_count', 'desc')
                ->paginate(24)
        );
    }

    public function show(Video $video): VideoShowResource
    {
        return new VideoShowResource(
            $video
                ->load([
                    'user' => function($q) {
                        return $q
                            ->withCount('subscribers')
                            ->withExists([
                                'subscribers as subscribed_by_auth_user' => fn($q) => $q->where('subscriber_id', auth('sanctum')->user()?->id),
                            ]);
                    },
                ])
                ->loadCount(['views', 'comments', 'likes', 'dislikes'])
        );
    }

    public function upload (FileRequest $request): JsonResponse {

        $folder = $request->get('resumableIdentifier');

        $chunk = $request->file('file');

        $name = $request->get('resumableChunkNumber'). ".part";

        $chunk->storeAs(Video::CHUNK_FOLDER.'/'.$folder, $name, 'local');

        if ($request->get('resumableTotalChunks') === $request->get('resumableChunkNumber')) {

            $title = Str::replace('.'.$chunk->getClientOriginalExtension(), '', $chunk->getClientOriginalName());

            $video = Video::create([
                'uuid' => Number::unique(),
                'title' => $title,
                'slug' => Str::slug($title),
                'original_file_name' => $chunk->getClientOriginalName(),
                'size' => $request->get('resumableTotalSize'),
                'status' => VideoStatus::DRAFT,
                'user_id' => Auth::user()->id
            ]);

            for ($i = 0; $i < Video::GENERATED_THUMBNAILS; $i++) {
                $video->thumbnails()->create([
                    'status' => ThumbnailStatus::PENDING
                ]);
            }

            BuildFullFileFromChunks::dispatch($folder, $video, $chunk->getClientOriginalExtension());

            return response()->json([
                'route' => route('user.videos.create', $video)
            ]);
        }

        $percentage = ceil($request->get('resumableChunkNumber') / $request->get('resumableTotalChunks') * 100);

        return response()->json([
            "done" => $percentage
        ]);
    }
}
