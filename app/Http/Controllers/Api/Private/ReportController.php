<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportRequest;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function report (ReportRequest $request) : Response {

        /** @var class-string<Video|Comment> $type */
        $type = $request->string('type')->value();

        $model = $type::findOrFail($request->integer('id'));

        $this->authorize('report', $model);

        $validated = $request->safe()->merge([
            'reportable_type' => $request->string('type'),
            'reportable_id' => $request->integer('id'),
        ])->toArray();

        Auth::user()->user_reports()->create($validated);

        return response()->noContent();
    }
}
