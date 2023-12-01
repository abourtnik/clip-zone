<?php

namespace App\Models\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

interface Reportable
{
    public function reports(): MorphMany;

    public function isReportedByUser(User $user): bool;

    public function reportByAuthUser(): MorphOne;
}
