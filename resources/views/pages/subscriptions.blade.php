@extends('layouts.default')

@section('content')
    <h5>Cette semaine</h5>
    <div class="row gx-4">
        @each('videos.card', $subscriptions->first()->videos, 'video')
    </div>
@endsection
