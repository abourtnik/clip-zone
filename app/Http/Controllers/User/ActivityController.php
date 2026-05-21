<?php

namespace App\Http\Controllers\User;

use App\Actions\Activity\ShowActivitiesAction;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class ActivityController extends Controller
{
    public function index(ShowActivitiesAction $showActivitiesAction) : View {

        return view('users.activity.index', $showActivitiesAction->data());
    }
}
