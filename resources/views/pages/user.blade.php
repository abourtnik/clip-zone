@extends('layouts.default')

@section('content')
    <img class="w-100" style="height: 250px" src="{{$user->background_url}}" alt="">
    <div class="border-bottom w-100 py-3  bg-gray-100">
        <div class="col-10 offset-1 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <img style="width: 100px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                <div class="ml-4">
                    <div>{{$user->username}}</div>
                    <div class="text-muted text-sm">{{$user->subscribers_count}} abonnés</div>
                </div>
            </div>
            @if(auth()->check())
                <subscribe-button isSubscribe="{{auth()->user()->isSubscribe($user) ? 'true' : 'false'}}" user="{{$user->id}}"/>
            @else
                <button>a</button>
            @endif
        </div>
    </div>
    @if($user->videos->first())
    <div class="row mt-4">
        <div class="col-10 offset-1">
            <div class="d-flex">
                <video controls class="w-100 h-100 border">
                    <source src="{{$user->videos->first()->url}}" type="video/mp4">
                </video>
                <div class="ml-4">
                    <div>{{$user->videos->first()->title}}</div>
                    <div class="text-muted text-sm my-2">{{$user->videos->first()->views}} vues • {{$user->videos->first()->created_at->diffForHumans()}}</div>
                    <div>{!! nl2br($user->videos->first()->description) !!}</div>
                </div>
            </div>
            <hr>
        </div>
    </div>
    @endif
    <div class="row mt-4">
        <div class="col-10 offset-1">
            <div class="row">
                @each('videos.card', $user->videos()->active()->latest('publication_date')->paginate(12), 'video')
            </div>
        </div>
    </div>
@endsection


