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
        <div class="col-12 col-md-10 offset-md-1">
            <div class="h-full card shadow">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fa-solid fa-history fa-4x mb-3"></i>
                        <h5 class="my-3">Your watch history is empty</h5>
                        <p class="text-muted">Start exploring videos</p>
                        <a href="{{route('pages.home')}}" class="btn btn-primary rounded-5 text-uppercase">
                            See videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
@endsection
