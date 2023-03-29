@extends('layouts.default')

@section('title', 'Liked videos')

@section('content')
    @forelse($data as $date => $interactions)
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
        </div>
        <hr>
        <div class="row gx-3 gy-4 mb-5">
            @foreach($interactions as $interaction)
                @include('videos.card', ['video' => $interaction->likeable])
            @endforeach
        </div>
    @empty
        <div class="h-full">
            <div class="d-flex justify-content-center align-items-center h-75 mt-5">
                <div class="w-75 border p-4 bg-light text-center">
                    <i class="fa-solid fa-thumbs-up fa-7x mb-3"></i>
                    <h2>Your like videos history is empty</h2>
                    <p class="text-muted">Start like videos</p>
                    <a href="{{route('pages.home')}}" class="btn btn-primary rounded-5 text-uppercase">
                        See videos
                    </a>
                </div>
            </div>
        </div>
    @endforelse
@endsection
