@extends('layouts.user')

@section('title', 'Activity')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>My activity</h2>
    </div>
    <hr>
    <form class="my-4 d-flex gap-3 align-items-end" method="GET">
        <div class="col">
            <label for="type" class="form-label fw-bold">Type</label>
            <select name="type" class="form-select" aria-label="Default select example">
                <option selected value="">All</option>
                @foreach(['comments' => 'Comments', 'interactions' => 'Likes & Dislikes', 'likes' => 'Likes', 'dislikes' => 'Dislikes'] as $value => $label)
                    <option @selected(($filters['type'] ?? null) === $value) value="{{$value}}">{{$label}}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <label for="activity_date_start" class="form-label fw-bold">Date start</label>
            <input type="datetime-local" name="date[]" class="form-control" id="activity_date_start" value="{{$filters['date'][0] ?? null}}">
        </div>
        <div class="col">
            <label for="activity_date_end" class="form-label fw-bold">Date end</label>
            <input type="datetime-local" name="date[]" class="form-control" id="activity_date_end" value="{{$filters['date'][1] ?? null}}">
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-outline-secondary" title="Search">
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
            <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                <i class="fa-solid fa-eraser"></i>
            </a>
        </div>
    </form>
    @forelse($activity_log as $date => $log)
        <div class="row mt-2 justify-content-center">
            <div class="col-12 mt-2">
                <h5 class="mb-3">{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
                @foreach($log as $activity)
                    @include('users.activity.types.'. $activity->type)
                @endforeach
            </div>
        </div>
        {{ $activity_log->links() }}
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
