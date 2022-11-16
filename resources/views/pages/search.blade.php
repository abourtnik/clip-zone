@extends('layouts.default')

@section('content')
    <div class="row gx-4">
        @each('videos.card', $videos, 'video')
    </div>
@endsection


