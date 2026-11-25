<?php

declare(strict_types=1);

namespace App\Actions\Video;

use App\Enums\SubtitleStatus;
use App\Enums\ThumbnailStatus;
use App\Enums\VideoStatus;
use App\Helpers\Number;
use App\Http\Requests\Video\FileRequest;
use App\Integrations\Transcription\TranscriptionContract;
use App\Jobs\ProcessVideo;
use App\Models\Video;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class UploadVideoAction
{
    public function execute(FileRequest $request): UploadResult
    {
        $folder = sprintf('%d/%s', Auth::id(), $request->string('resumableIdentifier'));

        $chunk = $request->file('file');

        $name = $request->integer('resumableChunkNumber'). ".part";

        $chunk->storeAs(Video::CHUNK_FOLDER.'/'.$folder, $name, 'local');

        $receivedChunk = $request->integer('resumableChunkNumber');
        $totalChunks = $request->integer('resumableTotalChunks');

        if ($receivedChunk < $totalChunks) {
            return UploadResult::inProgress((int) ceil($receivedChunk / $totalChunks * 100));
        }

        $video = $this->createDraftVideo($chunk, $request->integer('resumableTotalSize'));

        $this->createDefaultThumbnails($video);
        $this->createDefaultSubtitle($video);

        ProcessVideo::dispatch($folder, $video, Str::lower($chunk->getClientOriginalExtension()));

        return UploadResult::completed($video);
    }

    private function createDraftVideo(UploadedFile $chunk, int $size): Video
    {
        $title = Str::replace('.'.$chunk->getClientOriginalExtension(), '', $chunk->getClientOriginalName());

        return Video::create([
            'uuid' => Number::unique(),
            'title' => $title,
            'slug' => Str::slug($title),
            'original_file_name' => $chunk->getClientOriginalName(),
            'size' => $size,
            'status' => VideoStatus::DRAFT,
            'user_id' => Auth::id()
        ]);
    }

    private function createDefaultThumbnails(Video $video): void
    {
        for ($i = 0; $i < Video::GENERATED_THUMBNAILS; $i++) {
            $video->thumbnails()->create([
                'status' => ThumbnailStatus::PENDING
            ]);
        }
    }

    private function createDefaultSubtitle(Video $video): void
    {
        $transcriptionService = app(TranscriptionContract::class);

        if (!$transcriptionService->shouldCreateDefaultSubtitle($video->user)){
            return;
        }

        $video->subtitles()->create([
            'name' => 'Auto-generated',
            'status' => SubtitleStatus::PENDING,
            'is_enabled' => false,
        ]);
    }
}
