<?php

namespace App\Http\Controllers\Api\Private;

use App\Enums\SubtitleStatus;
use App\Helpers\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Subtitle\StoreSubtitleRequest;
use App\Http\Requests\Subtitle\ToggleSubtitleRequest;
use App\Http\Requests\Subtitle\UpdateSubtitleRequest;
use App\Http\Resources\Subtitle\SubtitleListResource;
use App\Models\Subtitle;
use App\Models\Video;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class SubtitlesController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource('App\Models\Subtitle,video', ['subtitle,video']);
    }

    public function index(Video $video): ResourceCollection
    {
        return $video->subtitles()
            ->latest()
            ->get()
            ->toResourceCollection(SubtitleListResource::class);
    }

    public function store(Video $video, StoreSubtitleRequest $request): SubtitleListResource
    {
        $validated = $request->safe()->merge([
            'file' => File::storeAndDelete($request->file('file'), Subtitle::FILE_FOLDER),
            'status' => SubtitleStatus::UPLOADED
        ])->toArray();

        $subtitle = $video->subtitles()->create($validated);

        return $subtitle->toResource(SubtitleListResource::class);
    }

    public function update(Video $video, Subtitle $subtitle, UpdateSubtitleRequest $request): SubtitleListResource
    {
        $validated = $request->safe()->merge([
            'file' => File::storeAndDelete($request->file('file'), Subtitle::FILE_FOLDER, $subtitle->file),
        ])->toArray();

        $subtitle->update($validated);

        return new SubtitleListResource($subtitle);
    }

    public function toggle(ToggleSubtitleRequest $request, Video $video, Subtitle $subtitle): SubtitleListResource
    {
        $subtitle->update(['is_enabled' => !$subtitle->is_enabled]);

        return new SubtitleListResource($subtitle);
    }

    public function destroy(Video $video, Subtitle $subtitle): Response
    {
        $subtitle->delete();
        return response()->noContent();
    }
}
