@extends('layouts.default')

@section('class', 'px-0')
@section('style', 'margin-top: 0 !important')

@section('content')
    <div class="position-relative">
        <img class="w-100" style="height: 250px" src="{{$user->background_url}}" alt="{{$user->username}} banner">
        @if($user->website)
            <a href="{{$user->website}}" class="position-absolute bottom-0 right-0 me-4 mb-4 p-2 text-white bg-dark bg-opacity-25 text-decoration-none text-uppercase">Website</a>
        @endif
    </div>
    <div class="w-100 bg-gray-100">
        <div class="col-10 offset-1">
            <div class="border-bottom">
                <div class="d-flex justify-content-between align-items-center py-4">
                    <div class="d-flex align-items-center">
                        <img style="width: 100px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                        <div class="ml-4">
                            <div>{{$user->username}}</div>
                            <div class="text-muted text-sm">{{trans_choice('subscribers', $user->subscribers_count)}} • {{trans_choice('videos', $user->videos()->active()->count())}}</div>
                        </div>
                    </div>
                    @auth
                        @if(auth()->user()->isNot($user))
                            <subscribe-button isSubscribe="{{auth()->user()->isSubscribe($user) ? 'true' : 'false'}}" user="{{$user->id}}"/>
                        @endif
                    @else
                        <button
                            type="button"
                            class="btn btn-danger text-uppercase"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-title="Voulez-vous vous abonner à cette chaîne ?"
                            data-bs-trigger="focus"
                            data-bs-html="true"
                            data-bs-content="Connectez-vous pour vous abonner à cette chaîne.<hr><a href='/login' class='btn btn-primary btn-sm'>Se connecter</a>"
                        >
                            S'abonner
                        </button>
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
            <div class="tab-content" style="min-height: 300px;">
                <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    @if($user->videos->first())
                        <div class="row mt-4">
                            <div class="d-flex">
                                <video controls class="w-50 h-100 border" controlsList="nodownload" poster="{{$user->videos->first()->poster_url}}">
                                    <source src="{{$user->videos->first()->url}}" type="video/mp4">
                                </video>
                                <div class="ml-4">
                                    <a href="{{route('pages.video', $user->videos->first())}}">{{$user->videos->first()->title}}</a>
                                    <div class="text-muted text-sm my-2">{{$user->videos->first()->views}} views • {{$user->videos->first()->created_at->diffForHumans()}}</div>
                                    <div>
                                        {!! nl2br(Str::limit($user->videos->first()->description, 600, '...')) !!}
                                        @if(Str::length($user->videos->first()->description) > 600)
                                            <a class="mt-2 d-block" href="{{route('pages.video', $user->videos->first())}}">Read more</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endif
                    <div class="mt-4">
                        <div class="row">
                            @each('videos.card', $user->videos->skip(1)->paginate(12), 'video')
                        </div>
                    </div>
                </div>
                <div class="tab-pane " id="videos" role="tabpanel" aria-labelledby="videos-tab">
                    <div class="d-flex align-items-center gap-2 my-4">
                        <button type="button" class="btn btn-sm btn-primary">Recently uploaded</button>
                        <button type="button" class="btn btn-sm btn-outline-primary">Popular</button>
                    </div>
                    <div class="row">
                        @each('videos.card', $user->videos->paginate(12), 'video')
                    </div>
                </div>
                <div class="tab-pane" id="about" role="tabpanel" aria-labelledby="about-tab">
                    <div class="row mt-4">
                        <div class="col-9">
                            <div class="card card-body">
                                <h6 class="card-title text-primary">Description</h6>
                                <hr>
                                {!! nl2br($user->description) !!}
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="card card-body">
                                <h6 class="card-title text-primary">Informations</h6>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item ps-0">Joined {{$user->created_at->format('d F Y')}}</li>
                                    <li class="list-group-item ps-0">{{$user->views}} views</li>
                                    @if($user->country)
                                        <li class="list-group-item ps-0">Country : {{$user->country_name}}</li>
                                    @endif
                                    @if($user->website)
                                        <li class="list-group-item ps-0">
                                            <a href="{{$user->website}}">Website</a>
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


