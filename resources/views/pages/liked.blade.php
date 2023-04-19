@extends('layouts.default')

@section('title', 'Liked videos')

@section('class', 'px-0 px-sm-2')

@section('content')
    @forelse($data as $date => $interactions)
        <div class="px-2 px-sm-0">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
            </div>
            <hr>
        </div>
        <div class="row gx-3 gy-4 mb-5 row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">
            @foreach($interactions as $interaction)
                @include('videos.card', ['video' => $interaction->likeable])
            @endforeach
        </div>
    @empty
        <div class="col-12 col-md-10 offset-md-1">
            <div class="h-full card shadow">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fa-solid fa-thumbs-up fa-4x mb-3"></i>
                        <h5 class="my-3">Your like videos history is empty</h5>
                        <p class="text-muted">Start like videos</p>
                        <a href="{{route('pages.home')}}" class="btn btn-primary rounded-5 text-uppercase">
                            See videos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforelse
@endsection
