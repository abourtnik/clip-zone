@extends('layouts.default')

@section('title', 'History')

@section('class', 'px-0 px-sm-3 mt-0 mt-sm-3')

@section('content')
    @forelse($data as $date => $views)
        <div class="px-2 px-sm-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
                @if($loop->index === 0)
                    <a href="{{route('history.clear')}}" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-trash"></i>
                        {{ __('Clear watch history') }}
                    </a>
                @endif
            </div>
            <hr>
        </div>
        <div class="row gx-3 gy-4 mb-5 row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">
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
                        <h5 class="my-3">{{ __('Your watch history is empty') }}</h5>
                        <p class="text-muted">{{ __('Start exploring videos') }}</p>
                        <a href="{{route('pages.home')}}" class="btn btn-primary rounded-5 text-uppercase">
                            {{ __('See videos') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
@endsection
