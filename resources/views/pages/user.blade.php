@extends('layouts.default')

@section('title', $user->username)

@section('class', 'px-0')
@section('style', 'margin-top: 0 !important')

@section('content')
    <div class="position-relative">
        <img class="w-100" style="height: 250px; object-fit: cover;" src="{{$user->banner_url}}" alt="{{$user->username}} banner">
        @if($user->website)
            <a href="//{{$user->website}}" class="position-absolute bottom-0 right-0 me-2 mb-2 p-1 text-white bg-dark bg-opacity-25 text-decoration-none fw-bold" rel="external nofollow" target="_blank">{{$user->website}}</a>
        @endif
    </div>
    <div class="w-100">
        <div class="col-10 offset-1">
            <div class="border-bottom">
                <div class="d-flex justify-content-between align-items-center py-4">
                    <div class="d-flex align-items-center">
                        <img style="width: 100px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
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
                            @if(!Auth()->user()->isSubscribeTo($user)) is-subscribe @endif
                            user="{{$user->id}}"
                        />
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
                <ul class="nav" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link user-tabs active" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                            Home
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link user-tabs" data-bs-toggle="tab" data-bs-target="#videos" type="button" role="tab" aria-controls="videos" aria-selected="false">
                            Videos
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link user-tabs" data-bs-toggle="tab" data-bs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="false">
                            About
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @if($user->pinned_video)
                        <div class="row mt-4">
                            <div class="d-flex">
                                <video controls class="w-50 h-100 border" controlsList="nodownload" poster="{{$user->pinned_video->thumbnail_url}}">
                                    <source src="{{$user->pinned_video->file_url}}" type="{{$user->pinned_video->mimetype}}">
                                </video>
                                <div class="ml-4">
                                    <a href="{{$user->pinned_video->route}}" class="text-decoration-none text-black fw-bold">{{$user->pinned_video->title}}</a>
                                    <div class="text-muted text-sm my-2">{{trans_choice('views', $user->pinned_video->views_count)}} • {{$user->pinned_video->created_at->diffForHumans()}}</div>
                                    <div>
                                        {!! nl2br(e($user->pinned_video->short_description)) !!}
                                        @if($user->pinned_video->description_is_long)
                                            <a class="mt-2 d-block text-decoration-none fw-bold" href="{{$user->pinned_video->route}}">Read more</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <user-videos
                            user="{{$user->id}}"
                            videos="{{$user->videos_count}}"
                            show-sort
                        />
                    @elseif($user->videos_count)
                        <div class="mt-4">
                            <user-videos
                                user="{{$user->id}}"
                                videos="{{$user->videos_count}}"
                                show-sort
                                exclude-pinned
                            />
                        </div>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-75 mt-4">
                            <div class="w-100 border p-4 bg-light text-center bg-white">
                                <i class="fa-solid fa-video-slash fa-5x mb-3"></i>
                                <p class="text-muted">This user has no videos</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane " id="videos" role="tabpanel" aria-labelledby="videos-tab">
                    @if($user->videos->count())
                        <user-videos user="{{$user->id}}" videos="{{$user->videos_count}}"  exclude-pinned/>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-75 mt-4">
                            <div class="w-100 border p-4 bg-light text-center bg-white">
                                <i class="fa-solid fa-video-slash fa-5x mb-3"></i>
                                <p class="text-muted">This user has no videos</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="tab-pane" id="about" role="tabpanel" aria-labelledby="about-tab">
                    <div class="row mt-4">
                        <div class="col-lg-9 order-2 order-lg-1">
                            <div class="card card-body">
                                <h6 class="card-title text-primary">Description</h6>
                                <hr>
                                {!! nl2br(e($user->description)) !!}
                            </div>
                        </div>
                        <div class="col-lg-3 order-1 order-lg-2 mb-4 mb-lg-0">
                            <div class="card card-body">
                                <h6 class="card-title text-primary">Informations</h6>
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
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
