@extends('layouts.default')

@section('title', $category->title . ' videos')

@section('class', 'px-0')
@section('style', 'margin-top: 0 !important')

@section('content')
    <div class="position-relative">
        <img class="w-100" src="{{$category->background_url}}" alt="{{$category->title}} background" style="height: 400px;object-fit: cover;">
        <h1 class="position-absolute top-50 start-50 translate-middle text-white" style="z-index: 1">{{__($category->title)}}</h1>
        <div class="position-absolute top-0 right-0 w-100 h-100 bg-dark bg-opacity-50"></div>
        <strong class="position-absolute bottom-0 end-0 text-white p-2" style="z-index: 1">{{trans_choice('videos', $category->videos_count)}}</strong>
    </div>
    <div class="container-fluid mt-3 my-3">
        <videos-area url="{{route('videos.category', $category)}}" />
    </div>
@endsection
