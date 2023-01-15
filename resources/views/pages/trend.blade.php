@extends('layouts.default')

@section('title', 'Trending')

@section('content')
    <videos-area url="{{route('videos.trend')}}" />
@endsection


