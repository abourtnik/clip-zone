@extends('layouts.default')

@section('title', 'Liked videos')

@section('content')
    @foreach($data as $date => $interactions)
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
        </div>
        <hr>
        <div class="row gx-3 gy-4">
            @foreach($interactions as $interaction)
                @include('videos.card', ['video' => $interaction->likeable])
            @endforeach
        </div>
    @endforeach
@endsection
