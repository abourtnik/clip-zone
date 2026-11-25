<?php

namespace App\Http\Controllers\Api\Private;

use App\Actions\Video\UploadVideoAction;
use App\Http\Requests\Video\FileRequest;
use App\Models\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class VideoController
{
    public function upload (FileRequest $request, UploadVideoAction $action): JsonResponse
    {
        $result = $action->execute($request);

        return $result->completed
            ? response()->json(['route' => route('user.videos.create', $result->video)])
            : response()->json(['done' => $result->progress]);
    }

    public function delete(Video $video): Response
    {
        $video->delete();

        return response()->noContent();
    }
}
