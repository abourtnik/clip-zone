@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-9">
            <video controls class="w-100 border" controlsList="nodownload">
                <source src="{{$video->url}}" type="{{$video->mimetype}}">
            </video>
            <div class="mt-3 d-flex align-items-center gap-2">
                @if($video->isPlanned())
                    <div class="d-flex alert alert-warning p-2 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-clock"></i>
                        <strong>Planned - {{$video->publication_date->format('d M Y H:i')}}</strong>
                    </div>
                @elseif(!$video->isActive())
                    <div class="d-flex alert alert-danger p-2 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-lock"></i>
                        <strong>Private</strong>
                    </div>
                @endif
                <h4 class="mb-0">{{$video->title}}</h4>
            </div>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="text-muted">{{$video->views}} views • {{$video->created_at->format('d F Y')}}</div>
                @auth()
                    <div class="d-flex gap-1">
                        <likes-button
                            active="{{ json_encode(['like' => auth()->user()->hasLiked($video), 'dislike' => auth()->user()->hasDisliked($video) ])}}"
                            model="{{get_class($video)}}"
                            target="{{$video->id}}"
                            count="{{ json_encode(['likes_count' => $video->likes()->count(), 'dislikes_count' => $video->dislikes()->count() ])}}"
                            auth="true"
                        />
                    </div>
                @else
                    <div class="d-flex justify-content-between gap-1">
                        <button
                            class="btn btn-sm btn-outline-success"
                            data-bs-toggle="popover"
                            data-bs-placement="left"
                            data-bs-title="Vous aimez cette vidéo ?"
                            data-bs-trigger="focus"
                            data-bs-html="true"
                            data-bs-content="Connectez-vous pour donner votre avis.<hr><a href='/login' class='btn btn-primary btn-sm'>Se connecter</a>"
                        >
                            <i class="fa-regular fa-thumbs-up"></i>
                            @if($video->likes->count())
                                <span>{{$video->likes->count()}}</span>
                            @endif
                        </button>
                        <button
                            class="btn btn-sm btn-outline-danger"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-title="Vous n'aimez pas cette vidéo ?"
                            data-bs-trigger="focus"
                            data-bs-html="true"
                            data-bs-content="Connectez-vous pour donner votre avis.<hr><a href='/login' class='btn btn-primary btn-sm'>Se connecter</a>"
                        >
                            <i class="fa-regular fa-thumbs-down"></i>
                            @if($video->likes->count())
                                <span>{{$video->likes->count()}}</span>
                            @endif
                        </button>
                    </div>
                @endauth
            </div>
            <hr>
            <div class="d-flex gap-3">
                <a href="{{route('pages.user', $video->user)}}">
                    <img class="rounded" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 48px;height: 48px;">
                </a>
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <a href="{{route('pages.user', $video->user)}}">{{$video->user->username}}</a>
                        <small class="text-muted d-block">{{trans_choice('subscribers', $video->user->subscribers_count)}}</small>
                    </div>
                    @auth
                        @if(auth()->user()->isNot($video->user))
                            <subscribe-button isSubscribe="{{auth()->user()->isSubscribe($video->user) ? 'true' : 'false'}}" user="{{$video->user->id}}"/>
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
            <description-box description="{{ $video->description }}" />
            <hr>
            <comments-area target="{{$video->id}}" auth="{{auth()->user()?->id}}"/>
        </div>
        <div class="col-3">
            @each('videos.card-secondary', $videos, 'video')
        </div>
    </div>
@endsection


