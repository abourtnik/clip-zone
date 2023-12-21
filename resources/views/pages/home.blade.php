@extends('layouts.default')

@section('title', 'Share and Watch Amazing Videos')
@section('description', 'Enjoy the videos and music you love, upload original content, and share it all with friends, family, and the world on '.config('app.name').'.')

@section('class', 'px-3 mt-3')

@section('content')
    <videos-area url="{{route('videos.home')}}" /></videos-area>
    @includeWhen($success, 'modals.premium')
@endsection


