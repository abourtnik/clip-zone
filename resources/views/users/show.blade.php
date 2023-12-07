@extends('layouts.default')

@section('title', $user->username)
@section('description', Str::limit($user->description, 155))

@section('class', 'px-0')
@section('style', 'margin-top: 0 !important')

@section('content')
    <div class="position-relative" x-data="{upload:false}" @mouseover="upload=true" @mouseleave="upload=false">
        <img class="w-100" style="height: 270px; object-fit: cover;" src="{{$user->banner_url}}" alt="{{$user->username}} banner">
        @if($user->website)
            <a href="//{{$user->website}}" class="position-absolute bottom-0 right-0 me-2 mb-2 p-1 text-white bg-dark bg-opacity-25 text-decoration-none fw-bold" rel="external nofollow" target="_blank">{{$user->website}}</a>
        @endif
        @if(Auth::user()?->is($user))
            <a href="{{route('user.edit')}}"
                x-show.important="upload"
                x-transition.duration.500ms
                class="position-absolute top-0 right-0 me-2 mt-2 p-2 text-white bg-dark bg-opacity-25 text-decoration-none fw-bold rounded-5"
            >
                <i class="fa-solid fa-2x fa-camera"></i>
            </a>
        @endauth
    </div>
    <div class="w-100">
        <div class="col-12 col-sm-10 offset-sm-1">
            <div>
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center py-4">
                    <div class="d-flex align-items-center mb-4 mb-sm-0">
                        <div class="d-flex align-items-center gap-4" x-data="{ upload: false }">
                            <div @mouseover="upload=true" @mouseleave="upload=false"  class="rounded-circle position-relative overflow-hidden" style="width: 110px">
                                <img style="width: 100px;height: 100px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                @if(Auth::user()?->is($user))
                                    <a href="{{route('user.edit')}}"
                                       x-show.important="upload"
                                       x-transition.duration.500ms
                                       x-transition.scale
                                       class="position-absolute top-50 start-50 translate-middle p-2 rounded-5 bg-dark bg-opacity-75 text-decoration-none text-center text-white"
                                    >
                                        <i class="fa-solid fa-camera fa-2x" style="font-size: 25px"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            <div>{{$user->username}}</div>
                            <div class="text-muted text-sm">
                                @if($user->show_subscribers)
                                    {{trans_choice('subscribers', $user->subscribers_count)}} •
                                @endif
                                {{trans_choice('videos', $user->videos_count)}}
                            </div>
                        </div>
                    </div>
                    @if(Auth::check() && Auth::user()->isNot($user))
                        <subscribe-button
                            @if(!$is_subscribed) is-subscribe @endif
                            user="{{$user->id}}">
                        </subscribe-button>
                    @elseif(!Auth::check())
                        <button
                            type="button"
                            class="btn btn-danger text-uppercase"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-title="Want to subscribe to this channel ?"
                            data-bs-trigger="focus"
                            data-bs-html="true"
                            data-bs-content="Sign in to subscribe to this channel.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                        >
                            Subscribe
                        </button>
                    @else
                        <div class="d-flex gap-2">
                            <a href="{{route('user.edit')}}" class="btn btn-primary">Customize channel</a>
                            <a href="{{route('user.videos.index')}}" class="btn btn-primary">Manage videos</a>
                        </div>
                    @endif
                </div>
                <ul class="nav mx-3 mx-sm-0 border-bottom" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link user-tabs active" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                            Home
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a id="videos_link" class="nav-link user-tabs" data-bs-toggle="tab" data-bs-target="#videos" type="button" role="tab" aria-controls="videos" aria-selected="false">
                            Videos
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link user-tabs" data-bs-toggle="tab" data-bs-target="#playlists" type="button" role="tab" aria-controls="playlists" aria-selected="false">
                            Playlists
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link user-tabs" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="false">
                            About
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content px-3 px-sm-0">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @if(!$user->videos_count && !$user->playlists_count)
                        <div class="alert alert-primary mt-4">Channel without content</div>
                    @else
                        @if($user->pinned_video)
                            <div class="row mt-4 mx-0 flex-row">
                                <div class="col-12 col-xl-7 col-xxl-5 px-0">
                                    <div class="ratio ratio-16x9">
                                        <video
                                            controls
                                            class="rounded-2"
                                            controlsList="nodownload"
                                            poster="{{$user->pinned_video->thumbnail_url}}"
                                            oncontextmenu="return false;"
                                            autoplay
                                        >
                                            <source src="{{$user->pinned_video->file_url}}" type="{{$user->pinned_video->mimetype}}">
                                        </video>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-5 col-xxl-7 mt-3 m-xl-0">
                                    <a href="{{$user->pinned_video->route}}" class="text-decoration-none text-black fw-bold">{{$user->pinned_video->title}}</a>
                                    <div class="text-muted text-sm my-2">{{trans_choice('views', $user->pinned_video->views_count)}} • {{$user->pinned_video->created_at->diffForHumans()}}</div>
                                    <div class="text-sm">
                                        <p class="mb-0">{!! nl2br($user->pinned_video->short_description) !!}</p>
                                        @if($user->pinned_video->description_is_long)
                                            <a class="mt-2 d-block text-decoration-none fw-bold" href="{{$user->pinned_video->route}}">Read more</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <hr>
                        @endif
                        @if($user->videos_count)
                            <div class="my-4 d-flex gap-3 align-items-center">
                                <h5 class="mb-0">Videos</h5>
                                <button
                                    class="link-primary bg-transparent text-decoration-none d-flex align-items-center gap-2 hover-primary"
                                    onclick="new bootstrap.Tab('#videos_link').show()"
                                >
                                    <i class="fa-solid fa-play"></i>
                                    See All
                                </button>
                            </div>
                            <div class="row g-3">
                                @foreach($user->videos as $video)
                                    @include('videos.card.card-3', $video)
                                @endforeach
                            </div>
                        @endif
                        @if($user->playlists_count)
                            <hr>
                            @foreach($user->playlists as $playlist)
                                <div class="mb-1 d-flex gap-3 align-items-center">
                                    <h5 class="mb-0">{{Str::limit($playlist->title, 80)}}</h5>
                                    <a class="link-primary bg-transparent text-decoration-none d-flex align-items-center gap-2 hover-primary" href="{{$playlist->route}}">
                                        <i class="fa-solid fa-play"></i>
                                        See All
                                    </a>
                                </div>
                                <p class="text-muted text-sm">{{Str::limit($playlist->description, 200)}}</p>
                                <div class="row g-3">
                                    @foreach($playlist->videos as $video)
                                        @include('videos.card.card-3', $video)
                                    @endforeach
                                </div>
                                <hr>
                            @endforeach
                        @endif
                    @endif
                </div>
                <div class="tab-pane" id="videos" role="tabpanel" aria-labelledby="videos-tab">
                    @if($user->videos->count())
                        <user-videos user="{{$user->id}}" videos="{{$user->videos_count}}"  exclude-pinned></user-videos>
                    @else
                        <div class="alert alert-primary mt-4">
                            This user has no videos
                        </div>
                    @endif
                </div>
                <div class="tab-pane" id="playlists" role="tabpanel" aria-labelledby="playlists-tab">
                    @if($user->playlists->count())
                        <div class="row gx-3 gy-4 mt-0">
                            @each('playlists.card', $user->playlists, 'playlist')
                        </div>
                    @else
                        <div class="alert alert-primary mt-4">
                            This user has no playlists
                        </div>
                    @endif
                </div>
                <div class="tab-pane" id="about" role="tabpanel" aria-labelledby="about-tab">
                    <div class="row mt-4">
                        <div class="col-lg-9 order-2 order-lg-1">
                            <div class="card card-body">
                                <h6 class="card-title text-primary">Description</h6>
                                <hr>
                                <x-expand-item max="1000">
                                    {!! nl2br(e($user->description)) !!}
                                </x-expand-item>
                            </div>
                        </div>
                        <div class="col-lg-3 order-1 order-lg-2 mb-4 mb-lg-0">
                            <div class="card card-body">
                                <h6 class="card-title text-primary mb-4">Informations</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item ps-0">Joined {{$user->created_at->format('d F Y')}}</li>
                                    <li class="list-group-item ps-0">{{trans_choice('views', $user->videos_views_count)}}</li>
                                    @if($user->country)
                                        <li class="list-group-item ps-0">Country : {{$user->country_name}}</li>
                                    @endif
                                    @if($user->website)
                                        <li class="list-group-item ps-0">
                                            <a rel="external nofollow" target="_blank" href="//{{$user->website}}">Website</a>
                                        </li>
                                    @endif
                                    @auth
                                    <li class="list-group-item ps-0">
                                        @can('report', $user)
                                            <button class="btn btn-secondary rounded-4 btn-sm px-3" data-bs-toggle="modal" data-bs-target="#report" data-id="{{$user->id}}" data-type="{{\App\Models\User::class}}">
                                                <i class="fa-regular fa-flag"></i>&nbsp;
                                                Report
                                            </button>
                                        @else
                                            @if($user->reportByAuthUser)
                                                <div class="rounded-4 d-flex alert alert-secondary px-3 py-2 align-items-center gap-2 mb-0 text-sm">
                                                    <i class="fa-regular fa-flag"></i>
                                                    <span>Reported {{$user->reportByAuthUser->created_at->diffForHumans()}}</span>
                                                </div>
                                            @endif
                                        @endcan
                                    </li>
                                    @endauth
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
