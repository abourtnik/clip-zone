@extends('layouts.default')

@section('title', $video->title)
@section('description', Str::limit($video->description, 155))

@section('class', 'mt-0 mt-lg-3 ms-0')

@section('content')
    <div class="row">
        <div class="col-lg-8 col-xl-8 col-xxl-8 offset-xxl-1 px-0 px-lg-3">
            <div class="ratio ratio-16x9">
                @if($video->isUploading)
                    <div class="bg-light-dark border border-light d-flex justify-content-center align-items-center">
                        <div class="text-center">
                            <div class="mb-2">Your video is processing ...</div>
                            <div class="text-muted text-sm">You will receive a notification once the processing is complete.</div>
                        </div>
                    </div>
                @else
                    <video controls class="w-100 border border-1 rounded" controlsList="nodownload" poster="{{$video->thumbnail_url}}" oncontextmenu="return false;">
                        <source src="{{$video->file_url}}#t={{$t}}" type="{{$video->mimetype}}">
                    </video>
                @endif
            </div>
            <div class="px-3 px-lg-0">
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
                    <h1 class="mb-0 h5">{{$video->title}}</h1>
                </div>
                <div class="mt-3 d-flex flex-column flex-xxl-row gap-3 justify-content-between align-items-start align-items-xxl-center">
                    <div class="text-muted">{{trans_choice('views', $video->views_count)}} • {{$video->publication_date?->format('d F Y') ?? $video->created_at->format('d F Y')}}</div>
                    @auth
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <interaction-button
                                active="{{ json_encode(['like' => $video->liked_by_auth_user, 'dislike' => $video->disliked_by_auth_user ])}}"
                                model="{{get_class($video)}}"
                                target="{{$video->id}}"
                                count="{{ json_encode(['likes_count' => $video->likes_count, 'dislikes_count' => $video->dislikes_count ])}}"
                                @if(!$video->show_likes) show-count @endif
                            >
                            </interaction-button>
                            <button class="btn btn-info rounded-4 btn-sm px-3" title="Share video" data-bs-toggle="modal" data-bs-target="#share" data-url="{{$video->route}}">
                                <i class="fa-solid fa-share"></i>&nbsp;
                                Share
                            </button>
                            @if(Auth::user()->is_premium)
                                <a href="{{route('video.download', $video)}}" class="btn btn-primary rounded-4 btn-sm px-3" title="Download video">
                                    <i class="fa-solid fa-download"></i>&nbsp;
                                    Download
                                </a>
                            @else
                                <button
                                    class="btn btn-primary rounded-4 btn-sm px-3"
                                    title="Download video"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Want to download this video ?"
                                    data-bs-trigger="focus"
                                    data-bs-html="true"
                                    data-bs-content="Upgrade to Premium for download videos.<hr><a href='/premium' class='btn btn-warning btn-sm text-white fw-bold'><i class='fa-solid fa-star'></i>&nbsp;Upgrade to Premium</a>"
                                >
                                    <i class="fa-solid fa-download"></i>&nbsp;
                                    Download
                                </button>
                            @endif
                            <button class="btn btn-warning btn-sm rounded-4 px-4" title="Save video" data-bs-toggle="modal" data-bs-target="#save" data-id="{{$video->id}}">
                                <i class="fa-regular fa-bookmark"></i>&nbsp;
                                Save
                            </button>
                            @can('report', $video)
                                @if($video->reported_by_auth_user)
                                    <div class="rounded-4 d-flex align-items-center alert alert-secondary px-3 py-1 gap-2 mb-0 text-sm">
                                        <i class="fa-regular fa-flag"></i>
                                        <span>Reported {{$video->reports->first()->created_at->diffForHumans()}}</span>
                                    </div>
                                @else
                                    <button class="btn btn-secondary btn-sm rounded-4 px-3" data-bs-toggle="modal" data-bs-target="#report" data-id="{{$video->id}}" data-type="{{\App\Models\Video::class}}">
                                        <i class="fa-regular fa-flag"></i>&nbsp;
                                        Report
                                    </button>
                                @endif
                            @endcan
                        </div>
                    @else
                        <div class="d-flex gap-2 align-items-center">
                            <div class="d-flex justify-content-between bg-light-dark rounded-4">
                                <button
                                    class="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-end"
                                    data-bs-toggle="popover"
                                    data-bs-placement="left"
                                    data-bs-title="Like this video ?"
                                    data-bs-trigger="focus"
                                    data-bs-html="true"
                                    data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                                >
                                    <i class="fa-regular fa-thumbs-up"></i>
                                    @if($video->show_likes && $video->likes_count)
                                        <span class="ml-1">{{$video->likes_count}}</span>
                                    @endif
                                </button>
                                <div class="vr h-75 my-auto"></div>
                                <button
                                    class="hover-grey btn btn-sm border border-0 text-black px-3 rounded-5 rounded-start"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Don't like this video ?"
                                    data-bs-trigger="focus"
                                    data-bs-html="true"
                                    data-bs-content="Sign in to make your opinion count.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                                >
                                    <i class="fa-regular fa-thumbs-down"></i>
                                    @if($video->show_likes && $video->dislikes_count)
                                        <span class="ml-1">{{$video->dislikes_count}}</span>
                                    @endif
                                </button>
                            </div>
                            <button class="btn btn-info rounded-4 btn-sm px-3" title="Share video" data-bs-toggle="modal" data-bs-target="#share" data-url="{{$video->route}}">
                                <i class="fa-solid fa-share"></i>&nbsp;
                                Share
                            </button>
                            <button
                                class="btn btn-secondary rounded-4 btn-sm px-3"
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
                            <img class="rounded-circle" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 48px;height: 48px;">
                        </a>
                        <div class="d-flex flex-column">
                            <a href="{{$video->user->route}}" class="text-decoration-none fw-bold">{{$video->user->username}}</a>
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
                            <div class="text-muted mb-0">No description provided</div>
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
                <hr class="d-none d-lg-block mt-4">
                @if($video->allow_comments)
                    <div class="d-none d-lg-block" id="comments_area"></div>
                    <div class="d-block d-lg-none">
                        <div class="d-flex align-items-center justify-content-between py-3 border-top border-bottom my-3" data-bs-toggle="offcanvas" data-bs-target="#comments-offcanvas">
                            <span>Comments • {{$video->comments_count}}</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                        <div class="offcanvas offcanvas-bottom" tabindex="-1" id="comments-offcanvas" style="min-height: 650px;">
                            <div class="offcanvas-header bg-light border d-flex justify-content-between align-items-center">
                                <h5 class="offcanvas-title">Comments</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div id="offcanvas-body" class="offcanvas-body"></div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-primary text-center">
                        <strong>Comments are turned off</strong>
                    </div>
                @endif
            </div>
        </div>
        @if($nextVideoUrl)
        <div class="col-lg-4 col-xl-4 col-xxl-3 px-0 px-sm-2">
            <div class="d-flex align-items-center justify-content-between gap-2 px-2 px-sm-0">
                <a href="{{$nextVideoUrl}}" class="btn btn-sm btn-primary d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-forward-step"></i>
                    <span>Next Video</span>
                </a>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="autoplay">
                    <label class="form-check-label" for="autoplay">Autoplay</label>
                </div>
            </div>
            <hr>
            <div>
                @each('videos.card-secondary',  $videos, 'video')
            </div>
        </div>
        @endif
    </div>
    @include('modals.share')
    @includeWhen(Auth::check(), 'modals.save')
@endsection

@pushIf($video->allow_comments , 'scripts')
    <script>
        const observer = new IntersectionObserver((entries) => {
            for (const entry of entries) {
                if(entry.isIntersecting) {
                    document.getElementById('comments_area').innerHTML ="<comments-area target='{{$video->id}}' auth='{{auth()->user()?->setAppends(['avatar_url'])->setVisible(['avatar_url', 'username'])}}' default-sort='{{$video->default_comments_sort}}' />";
                    observer.unobserve(entry.target)
                }
            }
        });

        observer.observe(document.getElementById('comments_area'));

        const commentsOffcanvas = document.getElementById('comments-offcanvas')
        let opened = false;
        commentsOffcanvas.addEventListener('show.bs.offcanvas', event => {
            if(!opened) {
                document.getElementById('offcanvas-body').innerHTML ="<comments-area target='{{$video->id}}' auth='{{auth()->user()?->setAppends(['avatar_url'])->setVisible(['avatar_url', 'username'])}}' default-sort='{{$video->default_comments_sort}}' />";
            }
            opened = true;
        })
    </script>
@endPushIf

@push('scripts')
    <script>

        // Volume

        const video = document.querySelector("video");

        const volume = JSON.parse(localStorage.getItem('volume')) || {value: 0.5, muted: false};

        video.volume = volume.value;
        video.muted = volume.muted;

        video.onvolumechange = (event) => {
            localStorage.setItem("volume", JSON.stringify({
                value: event.target.volume.toFixed(2),
                muted: event.target.muted
            }));
        };

        // Autoplay

        let autoplay = ((localStorage.getItem('autoplay') || false)  === 'true');

        document.getElementById('autoplay').checked = autoplay;

        document.getElementById('autoplay').addEventListener('change', (event) => {
            localStorage.setItem("autoplay", event.currentTarget.checked);
            autoplay = event.currentTarget.checked;
        })

        video.addEventListener('ended', (event) => {
            if(autoplay) {
                window.location.replace('{{$nextVideoUrl}}');
            }
        }, false);
    </script>
@endpush
