@extends('layouts.default')

@section('title', $category->title . ' videos')

@section('class', 'px-0')
@section('style', 'margin-top: 0 !important')

@section('content')
    <div class="position-relative">
        <img class="w-100" src="{{$category->background_url}}" alt="{{$category->title}} background" style="height: 400px;object-fit: cover;">
        <h1 class="position-absolute top-50 start-50 w-100 text-center translate-middle text-white z-1">{{__($category->title)}}</h1>
        <div class="position-absolute top-0 right-0 w-100 h-100 bg-dark bg-opacity-50"></div>
        <strong class="position-absolute bottom-0 end-0 text-white p-2 z-1">{{trans_choice('videos', $category->videos_count)}}</strong>
    </div>
    <div class="container-fluid px-0 px-sm-3 mt-3">
        @if($category->videos_count)
            <videos-area url="{{route('categories.videos', $category)}}" />
        @else
            <div class="h-full">
                <div class="row mt-5">
                    <div class="col-10 offset-1 border p-4 bg-light text-center bg-light">
                        <i class="fa-solid fa-video-slash fa-7x mb-3"></i>
                        <h2>No videos found</h2>
                        <p class="text-muted">Sorry, no videos are available yet. Please check back later for updates.</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
