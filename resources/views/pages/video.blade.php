@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-9">
            <video controls class="w-100 border">
                <source src="{{$video->url}}" type="video/mp4">
            </video>
            <div class="mt-3">
                <h4>{{$video->title}}</h4>

            </div>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="text-muted">{{$video->views}} views • {{$video->created_at->format('d F Y')}}</div>
                @auth()
                    <like-button
                        type="like"
                        active="{{ (bool) auth()->user()->hasLiked($video)}}"
                        model="{{get_class($video)}}"
                        target="{{$video->id}}"
                        count="{{$video->likes()->count()}}"
                        auth="true"
                    />
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
            <div class="d-flex mb-4 gap-3">
                <a href="{{route('pages.user', $video->user)}}">
                    <img class="rounded" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 48px;height: 48px;">
                </a>
                <div>
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <div>
                            <a href="{{route('pages.user', $video->user)}}">{{$video->user->username}}</a>
                            <small class="text-muted d-block">{{$video->user->subscribers_count}} abonnés</small>
                        </div>
                        @auth
                            <subscribe-button isSubscribe="{{auth()->user()->isSubscribe($video->user) ? 'true' : 'false'}}" user="{{$video->user->id}}"/>
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
                    <div class="mt-3">
                        {!! nl2br($video->description) !!}
                    </div>
                </div>
            </div>
            <hr>
            <comments-area target="{{$video->id}}" auth="{{auth()->user()?->id}}"/>
        </div>
        <div class="col-3">
           @foreach($videos as $video)
               <article class="d-flex mb-2 position-relative gap-2">
                   <a href="{{route('pages.video', $video)}}">
                       <div class="position-relative">
                           <img class="" src="{{$video->poster_url}}" alt="{{$video->title}}" style="height: 94px">
                           <small class="position-absolute bottom-0 right-0 p-1 text-white bg-dark">
                               {{$video->duration}}
                           </small>
                       </div>
                       <span style="position: absolute;inset: 0;"></span>
                   </a>
                   <div>
                       <small class="d-block fw-bold">{{$video->title}}</small>
                       <a href="{{route('pages.user', $video->user)}}" class="d-block text-muted text-sm position-relative">
                           {{$video->user->username}}
                       </a>
                       <small class="text-muted">{{$video->views}} views • {{$video->created_at->diffForHumans()}}</small>
                   </div>
               </article>
           @endforeach
        </div>
    </div>
@endsection


