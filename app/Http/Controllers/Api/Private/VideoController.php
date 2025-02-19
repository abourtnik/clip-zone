<?php

namespace App\Http\Controllers\Api\Private;

use App\Enums\ThumbnailStatus;
use App\Enums\VideoStatus;
use App\Helpers\Number;
use App\Http\Requests\Video\FileRequest;
use App\Jobs\BuildFullFileFromChunks;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class VideoController
{
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
