@extends('layouts.default')

@section('title', 'History')

@section('content')
    @forelse($data as $date => $views)
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
        <div class="row gx-3 gy-4 mb-5">
            @foreach($views as $view)
                @include('videos.card', ['video' => $view->video])
            @endforeach
        </div>
    @empty
        <div class="h-full">
            <div class="d-flex justify-content-center align-items-center h-75">
                <div class="w-75 border p-4 bg-light text-center">
                    <i class="fa-solid fa-history fa-7x mb-3"></i>
                    <h2>Your watch history is empty</h2>
                    <p class="text-muted">Some text</p>
                    <a href="{{route('pages.home')}}" class="btn btn-outline-primary">
                        See videos
                    </a>
                </div>
            </div>
        </div>
    @endforelse
@endsection
