@extends('layouts.default')

@section('title', $playlist->title)

@section('content')
    <div class="row justify-content-center gy-3">
        <div class="col-md-12 col-lg-5 col-xl-4 col-xxl-4 text-black">
            <div x-data="{favorite: {{$favorite_by_auth_user ? 'true' : 'false'}}}" class="text-black h-100 card">
                @if($playlist->first_video)
                    <img class="img-fluid card-img-top" src="{{$playlist->first_video->thumbnail_url}}" alt="{{$playlist->title}}">
                @endif
                <div class="card-body">
                    <h2 class="h4 my-3">{{$playlist->title}}</h2>
                    <div class="mt-3">by <a class="text-decoration-none fw-bold " href="{{$playlist->user->route}}">{{$playlist->user->username}}</a> </div>
                    <span class="badge bg-{{$playlist->status->color()}} my-3">
                        <i class="fa-solid fa-{{$playlist->status->icon()}}"></i>&nbsp;
                        {{$playlist->status->name()}}
                    </span>
                    <div class="d-flex align-items-center gap-1 mb-2">
                        <div class="text-sm">{{trans_choice('videos', $playlist->videos_count)}}</div>
                        <div class="text-sm">•</div>
                        <div class="text-sm">Created {{$playlist->created_at->diffForHumans()}}</div>
                    </div>
                    <div class="text-sm mb-3">Updated {{$playlist->updated_at->diffForHumans()}}</div>
                    <div class="d-flex gap-2">
                        @if($playlist->first_video)
                        <a href="{{$playlist->first_video->routeWithParams(['list' => $playlist->uuid])}}" class="btn btn-danger btn-sm d-flex gap-2 align-items-center">
                            <i class="fa-solid fa-play"></i>
                            <span>Play All</span>
                        </a>
                        @endif
                        @auth
                            <button x-show.important="!favorite" @click="favorite = true;" class="btn btn-primary d-flex align-items-center gap-2 btn-sm ajax-button" data-url="{{route('playlist.favorite', $playlist)}}">
                                <i class="fa-regular fa-heart"></i>
                                <span>Add to favorites</span>
                            </button>
                            <button x-show.important="favorite" @click="favorite = false;" class="btn btn-primary d-flex align-items-center gap-2 btn-sm ajax-button" data-url="{{route('playlist.remove-favorite', $playlist)}}">
                                <i class="fa-solid fa-heart"></i>
                                <span>Remove from favorites</span>
                            </button>
                        @else
                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                data-bs-toggle="popover"
                                data-bs-placement="right"
                                data-bs-title="Want to save this playlist ?"
                                data-bs-trigger="focus"
                                data-bs-html="true"
                                data-bs-content="Sign in to save this playlist.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                            >
                                <i class="fa-regular fa-heart"></i>
                                <span>Add to favorites</span>
                            </button>
                        @endauth
                    </div>
                    @if($playlist->user->is(Auth::user()))
                        <div class="d-flex align-items-start mt-2">
                            <a href="{{route('user.playlists.edit', $playlist)}}" class="btn btn-info btn-sm d-flex gap-2">
                                <i class="fa-solid fa-pen"></i>
                                <span>Edit playlist</span>
                            </a>
                        </div>
                    @endif
                    <div class="mt-3">
                        <x-expand-item max="150" color="dark">
                            {{$playlist->description}}
                        </x-expand-item>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-7 col-xl-8 col-xxl-8">
            <div class="card card-body p-0">
                @if($playlist->videos_count)
                    <div class="overflow-auto" style="height: 700px">
                        <ul class="list-group list-group-flush">
                            @foreach($playlist->videos as $key => $video)
                                <div class="d-flex gap-3 align-items-center list-group-item list-group-item-action px-2 px-sm-3 py-3 py-sm-3">
                                    @if($video->user->is(Auth::user()) || $video->is_public)
                                        @include('videos.card-secondary', ['video' => $video, 'playlist_video' => true])
                                    @else
                                        <div class="w-100 align-items-center">
                                            <div class="col-12">
                                                <div class="bg-secondary text-white d-flex flex-column justify-content-center align-items-center w-100 gap-2" style="height: 100px">
                                                    <i class="fa-solid fa-lock fa-1x"></i>
                                                    <div class="text-center text-sm">
                                                        <div class='text-sm fw-bold'>This video is private</div>
                                                        <div class='text-sm'>The author update video status to private</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        This playlist does not contain any videos at the moment.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
