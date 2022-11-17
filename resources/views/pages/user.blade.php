@extends('layouts.default')

@section('content')
    <img class="w-100" style="height: 250px" src="{{$user->background_url}}" alt="">
    <div class="border-bottom w-100 py-3  bg-gray-100">
        <div class="col-10 offset-1 d-flex justify-content-between align-items-center">
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
    </div>
    @if($user->first_active_video)
    <div class="row mt-4">
        <div class="col-10 offset-1">
            <div class="d-flex">
                <video controls class="w-50 h-100 border" controlsList="nodownload">
                    <source src="{{$user->first_active_video->url}}" type="video/mp4">
                </video>
                <div class="ml-4">
                    <a href="{{route('pages.video', $user->first_active_video)}}">{{$user->first_active_video->title}}</a>
                    <div class="text-muted text-sm my-2">{{$user->first_active_video->views}} vues • {{$user->first_active_video->created_at->diffForHumans()}}</div>
                    <div>
                        {!! nl2br(Str::limit($user->first_active_video->description, 600, '...')) !!}
                        @if(Str::length($user->first_active_video->description) > 600)
                            <a class="mt-2 d-block" href="{{route('pages.video', $user->first_active_video)}}">Lire la suite</a>
                        @endif
                    </div>

                </div>
            </div>
            <hr>
        </div>
    </div>
    @endif
    <div class="row mt-4">
        <div class="col-10 offset-1">
            <div class="row">
                @each('videos.card', $user->others_active_videos->paginate(12), 'video')
            </div>
        </div>
    </div>
@endsection


