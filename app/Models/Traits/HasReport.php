<?php

namespace App\Models\Traits;

use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

trait HasReport
{
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function isReportedByUser(User $user): bool
    {
        return $this->morphOne(Report::class, 'reportable')->where('user_id', $user->id)->exists();
    }

    public function reportByAuthUser(): MorphOne
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('sanctum')->user();

        return $this->morphOne(Report::class, 'reportable')->where('user_id', $user?->id);
    }
}
