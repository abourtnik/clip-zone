@extends('layouts.default')

@section('content')
    @foreach($data as $date => $interactions)
        <div class="d-flex justify-content-between align-items-center">
            <h5>{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
            @if($loop->index === 0)
                <a href="{{route('history.clear')}}" class="btn btn-danger btn-sm">
                    Action
                </a>
            @endif
        </div>
        <hr>
        <div class="row">
            @foreach($interactions as $interaction)
                @include('videos.card', ['video' => $interaction->likeable])
            @endforeach
        </div>
    @endforeach
@endsection
