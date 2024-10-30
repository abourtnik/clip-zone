@extends('layouts.default')

@section('title', 'Trending')
@section('description', 'The pulse of what&#39;s trending on '.config('app.name').'. Check out the latest music videos, trailers, comedy clips, and everything else that people are watching right now.')

@section('class', 'px-1 px-sm-3 mt-0 mt-sm-3')

@section('content')
    <videos-area url="{{route('videos.trend')}}" />
@endsection


