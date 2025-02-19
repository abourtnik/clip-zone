<?php

namespace App\Http\Controllers\Api\Private;

use App\Http\Controllers\Controller;
use App\Http\Requests\Report\ReportRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function report (ReportRequest $request) : Response {

        $type = $request->get('type');

        $model = $type::findOrFail($request->get('id'));

        $this->authorize('report', $model);

        $validated = $request->safe()->merge([
            'reportable_type' => $request->get('type'),
            'reportable_id' => $request->get('id'),
        ])->toArray();

        Auth::user()->user_reports()->create($validated);

        return response()->noContent();
    }
}
