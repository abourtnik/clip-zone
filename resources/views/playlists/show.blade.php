@extends('layouts.default')

@section('title', $playlist->title)

@section('content')
    <div class="row">
        <div class="col-3">
            <div class="bg-secondary text-white rounded-4 py-4 px-3" style="background: linear-gradient(to bottom, rgba(89,69,61,0.800) 0%, rgba(89,69,61,0.298) 33%, rgba(89,69,61,0.800) 100%);">
                <img class="img-fluid w-100 rounded-4" src="{{$playlist->videos->first()->thumbnail_url}}" alt="{{$playlist->title}}" style="width: 360px; height: 202px;object-fit: cover;">
                <h2 class="h4 my-3">{{$playlist->title}}</h2>
                <div class="mt-3">by <a class="text-decoration-none text-white fw-bold " href="{{$playlist->user->route}}">{{$playlist->user->username}}</a> </div>
                <span class="badge bg-{{$playlist->status->color()}} my-3">
                    <i class="fa-solid fa-{{$playlist->status->icon()}}"></i>&nbsp;
                    {{$playlist->status->name()}}
                </span>
                <div class="d-flex align-items-center gap-1 mb-3">
                    <div class="text-sm">{{trans_choice('videos', $playlist->videos_count)}}</div>
                    <div class="text-sm">•</div>
                    <div class="text-sm">{{$playlist->created_at->diffForHumans()}}</div>
                </div>
                <p class="text-sm">{{$playlist->description}}</p>
            </div>
        </div>
        <div class="col-9">
            @foreach($playlist->videos as $key => $video)
                <div class="d-flex gap-3 align-items-center">
                    <span>{{$key + 1}}</span>
                    @include('videos.card-secondary', $video)
                </div>
            @endforeach
        </div>
    </div>
@endsection
