@extends('layouts.default')

@section('title', $video->title)

@section('content')
    <div class="row">
        <div class="col-lg-7 col-xl-8 col-xxl-9">
            <video controls class="w-100 border" controlsList="nodownload" poster="{{$video->thumbnail_url}}">
                <source src="{{route('video.file', $video)}}" type="{{$video->mimetype}}">
            </video>
            <div class="mt-3 d-flex align-items-center gap-3">
                @if($video->is_planned)
                    <div class="d-flex alert alert-warning px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-clock"></i>
                        <strong>Planned - {{$video->scheduled_date->format('d M Y H:i')}}</strong>
                    </div>
                @elseif($video->is_private)
                    <div class="d-flex alert alert-danger px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-lock"></i>
                        <strong>Private</strong>
                    </div>
                @elseif($video->is_unlisted)
                    <div class="d-flex alert alert-secondary px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-eye-slash"></i>
                        <strong>Unlisted</strong>
                    </div>
                @elseif($video->is_draft)
                    <div class="d-flex alert alert-secondary px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-file"></i>
                        <strong>Draft</strong>
                    </div>
                @endif
                <h4 class="mb-0">{{$video->title}}</h4>
            </div>
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-muted">{{trans_choice('views', $video->views_count)}} • {{$video->publication_date?->format('d F Y') ?? $video->created_at->format('d F Y')}}</div>
                @auth
                    <div class="d-flex gap-2 align-items-center">
                        <interaction-button
                            active="{{ json_encode(['like' => $video->liked_by_auth_user, 'dislike' => $video->disliked_by_auth_user ])}}"
                            model="{{get_class($video)}}"
                            target="{{$video->id}}"
                            count="{{ json_encode(['likes_count' => $video->likes_count, 'dislikes_count' => $video->dislikes_count ])}}"
                            @if(!$video->show_likes) show-count="false" @endif
                        >
                        </interaction-button>
                        <button class="btn btn-info rounded-4" title="Share video" data-bs-toggle="modal" data-bs-target="#share" data-url="{{$video->route}}">
                            <i class="fa-solid fa-share"></i>&nbsp;
                            Share
                        </button>
                        <a href="{{route('video.download', $video)}}" class="btn btn-primary rounded-4" title="Download video">
                            <i class="fa-solid fa-download"></i>&nbsp;
                            Download
                        </a>
                        @if($video->user->isNot(Auth::user()) && $video->reports->count())
                            <div class="rounded-4 d-flex alert alert-secondary px-3 py-2 align-items-center gap-2 mb-0 text-sm">
                                <i class="fa-regular fa-flag"></i>
                                <span>Reported {{$video->reports->first()->created_at->diffForHumans()}}</span>
                            </div>
                        @elseif($video->user->isNot(Auth::user()))
                            <button class="btn btn-secondary rounded-4" data-bs-toggle="modal" data-bs-target="#report" data-id="{{$video->id}}" data-type="{{\App\Models\Video::class}}">
                                <i class="fa-regular fa-flag"></i>&nbsp;
                                Report
                            </button>
                        @endif
                    </div>
                @else
                    <div class="d-flex gap-2 align-items-center">
                        <div class="d-flex justify-content-between gap-1 bg-light-dark border border-primary rounded-4 p-1">
                            <button
                                class="d-flex justify-content-between align-items-center btn btn-sm border border-0 text-black"
                                data-bs-toggle="popover"
                                data-bs-placement="left"
                                data-bs-title="Like this video ?"
                                data-bs-trigger="focus"
                                data-bs-html="true"
                                data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                            >
                                <i class="fa-regular fa-thumbs-up"></i>
                                @if($video->show_likes && $video->likes_count)
                                    <span>{{$video->likes_count}}</span>
                                @endif
                            </button>
                            <div class="vr"></div>
                            <button
                                class="d-flex justify-content-between align-items-center btn btn-sm border border-0 text-black"
                                data-bs-toggle="popover"
                                data-bs-placement="right"
                                data-bs-title="Don't like this video ?"
                                data-bs-trigger="focus"
                                data-bs-html="true"
                                data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                            >
                                <i class="fa-regular fa-thumbs-down"></i>
                                @if($video->show_likes && $video->dislikes_count)
                                    <span>{{$video->dislikes_count}}</span>
                                @endif
                            </button>
                        </div>
                        <button
                            class="btn btn-secondary rounded-4"
                            data-bs-toggle="popover"
                            data-bs-placement="right"
                            data-bs-title="Need to report the video?"
                            data-bs-trigger="focus"
                            data-bs-html="true"
                            data-bs-content="Sign in to report inappropriate content.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                        >
                            <i class="fa-regular fa-flag"></i>&nbsp;
                            Report
                        </button>
                    </div>
                @endauth
            </div>
            <hr>
            <div class="d-flex gap-3 justify-content-between align-items-center">
                <div class="d-flex gap-3">
                    <a href="{{$video->user->route}}">
                        <img class="rounded" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 48px;height: 48px;">
                    </a>
                    <div class="d-flex flex-column">
                        <a href="{{$video->user->route}}" class="text-decoration-none">{{$video->user->username}}</a>
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
                            data-bs-title="Want to subscribe to this channel ?"
                            data-bs-trigger="focus"
                            data-bs-html="true"
                            data-bs-content="Sign in to subscribe to this channel.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                        >
                            Subscribe
                        </button>
                    @elseif(auth()->user()->isNot($video->user))
                        <subscribe-button
                            @if(!Auth()->user()->isSubscribeTo($video->user)) is-subscribe @endif
                            user="{{$video->user->id}}">
                        </subscribe-button>
                    @endif
                </div>
            </div>
            @if($video->description && $video->description_is_long)
                <div class="my-4 card pointer-event" style='cursor: pointer;' x-data="{ open: false }" @click="open=!open">
                    <div class="card-body">
                        <template x-if="open">
                            <p>
                                {!! nl2br($video->parsed_description) !!}
                            </p>
                        </template>
                        <template x-if="!open">
                            <p>
                                {!! nl2br($video->short_description) !!}
                            </p>
                        </template>
                        <div class="text-primary fw-bold mt-1" x-text="open ? 'Show less': 'Show more'"></div>
                    </div>
                </div>
            @elseif($video->description)
                <div class="card my-4">
                    <div class="card-body">
                        {!! nl2br($video->parsed_description) !!}
                    </div>
                </div>
            @else
                <div class="my-4 card">
                    <div class="card-body">
                        <div class="alert alert-primary mb-0 fw-bold">No description provided</div>
                    </div>
                </div>
            @endif
            <div class="d-flex gap-2 align-items-center">
                @if($video->category)
                    <a href="{{$video->category->route}}" class="d-flex alert alert-info px-2 py-1 align-items-center gap-2 mb-0 text-decoration-none">
                        <i class="fa-solid fa-{{$video->category->icon}}"></i>
                        <strong>{{$video->category->title}}</strong>
                    </a>
                @endif
                @if($video->language)
                    <div class="d-flex alert alert-info px-2 py-1 align-items-center gap-2 mb-0">
                        <i class="fa-solid fa-language"></i>
                        <strong>{{$video->language->name}}</strong>
                    </div>
                @endif
            </div>
            <hr>
            @if($video->allow_comments)
                <div id="comments_area"></div>
            @else
                <div class="alert alert-primary text-center">
                    <strong>Comments are turned off</strong>
                </div>
            @endif
        </div>
        <div class="col-lg-5 col-xl-4 col-xxl-3">
            @each('videos.card-secondary',  $videos, 'video')
        </div>
    </div>
@endsection

@pushIf($video->allow_comments , 'scripts')
    <script>
        const observer = new IntersectionObserver((entries) => {
            for (const entry of entries) {
                if(entry.isIntersecting) {
                    document.getElementById('comments_area').innerHTML ="<comments-area target='{{$video->id}}' auth='{{auth()->user()?->id}}' default-sort='{{$video->default_comments_sort}}' />";
                    observer.unobserve(entry.target)
                }
            }
        });

        observer.observe(document.getElementById('comments_area'));
    </script>
@endPushIf
