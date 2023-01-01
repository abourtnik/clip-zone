@extends('layouts.user')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>My activity</h2>
    </div>
    <hr>
    @forelse($activity_log as $date => $log)
        <div class="row">
            <div class="col-1 d-flex flex-column justify-content-center align-items-center">
                <div class="alert alert-secondary p-1 mb-0">
                    {{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}
                </div>
                <div class="h-100 border border-secondary" style="width: 1px"></div>
            </div>
            <div class="col-11 mt-4">
                @foreach($log as $activity)
                    @include('users.activity.types.'. $activity->type)
                @endforeach
            </div>
        </div>
    @empty
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-eye-slash fa-2x"></i>
                    <h5 class="my-3">No activity yet</h5>
                    <div class="text-muted my-3">Begin with interact first video</div>
                    <a href="{{route('pages.home')}}" class="btn btn-success">
                        <i class="fa-solid fa-play"></i>
                        Start interaction
                    </a>
                </div>
            </div>
        </div>
    @endforelse
@endsection
