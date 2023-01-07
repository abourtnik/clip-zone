@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-9">
            <video controls class="w-100 border" controlsList="nodownload" poster="{{$video->poster_url}}">
                <source src="{{$video->url}}" type="{{$video->mimetype}}">
            </video>
            <div class="mt-3 d-flex align-items-center gap-2">
                @if($video->isPlanned())
                    <div class="d-flex alert alert-warning px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-clock"></i>
                        <strong>Planned - {{$video->publication_date->format('d M Y H:i')}}</strong>
                    </div>
                @elseif(!$video->isActive())
                    <div class="d-flex alert alert-danger px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-lock"></i>
                        <strong>Private</strong>
                    </div>
                @endif
                <h4 class="mb-0">{{$video->title}}</h4>
            </div>
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <div class="text-muted">{{trans_choice('views', $video->views_count)}} • {{$video->created_at->format('d F Y')}}</div>
                @auth()
                    <div class="d-flex gap-1">
                        <likes-button
                            active="{{ json_encode(['like' => $video->liked_by_auth_user, 'dislike' => $video->disliked_by_auth_user ])}}"
                            model="{{get_class($video)}}"
                            target="{{$video->id}}"
                            count="{{ json_encode(['likes_count' => $video->likes_count, 'dislikes_count' => $video->dislikes_count ])}}"
                            auth="true"
                            showCount="{{$video->show_likes ? 'true': 'false'}}"
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
                            @if($video->likes_count)
                                <span>{{$video->likes_count}}</span>
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
                            @if($video->dislikes_count)
                                <span>{{$video->dislikes_count}}</span>
                            @endif
                        </button>
                    </div>
                @endauth
            </div>
            <hr>
            <div class="d-flex gap-3 justify-content-between align-items-center">
                <div class="d-flex gap-3">
                    <a href="{{route('pages.user', $video->user)}}">
                        <img class="rounded" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 48px;height: 48px;">
                    </a>
                    <div class="d-flex flex-column">
                        <a href="{{route('pages.user', $video->user)}}">{{$video->user->username}}</a>
                        @if($video->user->show_subscribers)
                            <small class="text-muted d-block">{{trans_choice('subscribers', $video->user->subscribers_count)}}</small>
                        @endif
                    </div>
                </div>
                <div class="">
                    @if(!Auth::check())
                        <button
                            type="button"
                            class="btn btn-danger"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-title="Want to subscribe to this channel?"
                            data-bs-trigger="focus"
                            data-bs-html="true"
                            data-bs-content="Sign in to subscribe to this channel.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                        >
                            Subscribe
                        </button>
                    @elseif(auth()->user()->isNot($video->user))
                       <subscribe-button isSubscribe="{{auth()->user()->isSubscribe($video->user) ? 'true' : 'false'}}" user="{{$video->user->id}}"/>
                    @endif
                </div>
            </div>
            @if($video->description_is_long)
                <div class="my-4 card pointer-event" style='cursor: pointer;' x-data="{ open: false }" @click="open=!open">
                    <div class="card-body">
                        <template x-if="open">
                            <p>
                                {{$video->description}}
                            </p>
                        </template>
                        <template x-if="!open">
                            <p>
                                {{$video->short_description}}
                            </p>
                        </template>
                        <div class="text-primary fw-bold mt-1" x-text="open ? 'Show less': 'Show more'"></div>
                    </div>
                </div>
            @else
                <div class="card my-4">
                    <div class="card-body">
                        {{$video->description}}
                    </div>
                </div>
            @endif
            <div class="d-flex gap-2 align-items-center">
                @if($video->category)
                    <div class="d-flex alert alert-info px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-{{$video->category->icon}}"></i>
                        <strong>{{$video->category->title}}</strong>
                    </div>
                @endif
                @if($video->language)
                    <div class="d-flex alert alert-info px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-language"></i>
                        <strong>{{$video->language_name}}</strong>
                    </div>
                @endif
            </div>
            <hr>
            @if($video->allow_comments)
                <comments-area target="{{$video->id}}" auth="{{auth()->user()?->id}}" defaultSort="{{$video->default_comments_sort}}"/>
            @else
                <div class="alert alert-primary text-center">
                    <strong>Comments are turned off</strong>
                </div>
            @endif
        </div>
        <div class="col-3">
            @each('videos.card-secondary', $videos, 'video')
        </div>
    </div>
@endsection
