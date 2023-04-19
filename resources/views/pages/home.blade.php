@extends('layouts.default')

@section('title', 'Share and Watch Amazing Videos')

@section('class', 'px-0 px-sm-2')

@section('content')
    <videos-area url="{{route('videos.home')}}" />
@endsection


