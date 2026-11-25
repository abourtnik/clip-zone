<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\SubtitleStatus;
use App\Events\Subtitles\SubtitleError;
use App\Http\Controllers\Controller;
use App\Integrations\Transcription\TranscriptionContract;
use App\Jobs\ProcessTranscript;
use App\Models\Subtitle;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TranscriptionWebhookController extends Controller
{
    public function index(Request $request, TranscriptionContract $transcriptionService): Response
    {
        $data = $request->validate([
            'transcript_id' => ['required', 'string'],
            'status'        => ['required', 'string', 'in:completed,error'],
        ]);

        $subtitle = Subtitle::query()
            ->where('provider_id', $data['transcript_id'])
            ->first();

        if (!$subtitle || in_array($subtitle->status, [SubtitleStatus::GENERATED, SubtitleStatus::ERROR])) {
            return response()->noContent();
        }

        if ($data['status'] === $transcriptionService::STATUS_ERROR) {
            $subtitle->update(['status' => SubtitleStatus::ERROR]);
            SubtitleError::dispatch($subtitle);
            return response()->noContent();
        }

        ProcessTranscript::dispatch($subtitle->id);

        return response()->noContent();
    }
}
