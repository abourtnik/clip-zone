@extends('layouts.default')

@section('title', 'Trending')

@section('class', 'px-0 px-sm-2 mt-0 mt-sm-3')

@section('content')
    <videos-area url="{{route('videos.trend')}}" />
@endsection


