@extends('layouts.default')

@section('title', 'Share and Watch Amazing Videos')
@section('description', 'Enjoy the videos and music you love, upload original content, and share it all with friends, family, and the world on '.config('app.name').'.')

@section('class', 'px-0 px-sm-3 mt-0 mt-sm-3')

@section('content')
    <videos-area url="{{route('videos.index')}}" /></videos-area>
    @includeWhen($success, 'modals.premium')
@endsection


