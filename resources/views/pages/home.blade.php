@extends('layouts.default')

@section('title', 'Share and Watch Amazing Videos')

@section('class', 'px-0 px-sm-2 mt-0 mt-sm-3')

@section('content')
    <videos-area url="{{route('videos.home')}}" /></videos-area>
    @includeWhen($success, 'modals.premium')
@endsection


