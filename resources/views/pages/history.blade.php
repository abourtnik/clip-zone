@extends('layouts.default')

@section('content')
    @foreach($data as $date => $views)
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
            @if($loop->index === 0)
                <a href="{{route('history.clear')}}" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i>
                    Clear watch history
                </a>
            @endif
        </div>
        <hr>
        <div class="row">
            @foreach($views as $view)
                @include('videos.card', ['video' => $view->video])
            @endforeach
        </div>
    @endforeach
@endsection
