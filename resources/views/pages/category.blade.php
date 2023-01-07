@extends('layouts.default')

@section('class', 'px-0')
@section('style', 'margin-top: 0 !important')

@section('content')
    <img class="w-100" src="{{$category->background_url}}" alt="{{$category->title}} background" style="height: 270px;object-fit: cover;">
    <div class="bg-light-dark d-flex align-items-center justify-content-between py-4 px-3">
        <div class="d-flex gap-4 align-items-center">
            <div class="rounded-circle bg-info p-3">
                <i class="fa-solid fa-{{$category->icon}} fa-2x text-white"></i>
            </div>
            <h5>{{$category->title}}</h5>
        </div>
        <strong>
            {{$category->videos_count}} Videos
        </strong>
    </div>
    <div class="container-fluid mt-3 my-3">
        <videos-area url="{{route('videos.category', $category)}}" />
    </div>
@endsection
