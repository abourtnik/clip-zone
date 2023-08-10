<?php

namespace App\Http\Controllers\Admin;
use App\Models\Plan;
use Illuminate\Contracts\View\View;

class PlanController
{
    public function index () : View {
        return view('admin.plans.index', [
            'plans' => Plan::query()->latest()->get()
        ]);
    }
}
