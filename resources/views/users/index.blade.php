@extends('layouts.user')

@section('content')
    <div class="row mb-4">
        <div class="col-3">
            <div class="card shadow border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">Uploaded videos</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-video fa-2x"></i>
                        <p class="card-text text-center fs-1">{{auth()->user()->videos()->count()}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card shadow border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">Subscribers</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-user fa-2x"></i>
                        <p class="card-text text-center fs-1">{{auth()->user()->subscribers_count}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card shadow border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">Views</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-eye fa-2x"></i>
                        <p class="card-text text-center fs-1">{{auth()->user()->views}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <div class="card shadow border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-center text-primary">Interactions</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-5">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-thumbs-up fa-2x"></i>
                            <p class="card-text text-center fs-1">{{auth()->user()->videos_likes()->count()}}</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-thumbs-down fa-2x"></i>
                            <p class="card-text text-center fs-1">{{auth()->user()->videos_dislikes()->count()}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-7">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-video"></i>
                    <h5 class="card-title mb-0">
                        Last uploaded videos
                    </h5>
                </div>
                <div class="card-body d-flex justify-content-center">
                    @if(auth()->user()->videos->count())
                        <table class="table">
                        <thead>
                        <tr>
                            <th scope="col" style="width: 40%">Video</th>
                            <th scope="col" style="width: 12%">Status</th>
                            <th scope="col" style="width: 10%">Views</th>
                            <th scope="col" style="width: 10%">Comments</th>
                            <th scope="col" style="width: 18%">Interactions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(auth()->user()->videos as $video)
                            <tr>
                                <td class="align-middle d-flex gap-3">
                                    <a href="{{route('pages.video', $video)}}">
                                        <img src="{{$video->poster_url}}" alt="" style="width: 99px;">
                                    </a>
                                    <div>{{$video->title}}</div>
                                </td>
                                <td class="align-middle">@include('users.videos.partials.status')</td>
                                <td class="align-middle">{{$video->views}}</td>
                                <td class="align-middle">{{$video->comments()->count()}}</td>
                                <td class="align-middle">@include('users.videos.partials.interactions')</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-upload fa-2x"></i>
                            <h5 class="my-3">Import your first video</h5>
                            <a href="{{route('user.videos.create')}}" class="btn btn-success">
                                Import
                            </a>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See all
                        </a>
                        <a href="{{route('user.videos.create')}}" class="btn btn-success">
                            <i class="fa-solid fa-upload"></i>
                            Import new video
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-user"></i>
                    <h5 class="card-title mb-0">
                        Last subscribers
                    </h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->subscribers->count())
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Channel</th>
                            <th scope="col">Date</th>
                            <th scope="col">Subscribers</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(auth()->user()->subscribers as $subscriber)
                            <tr>
                                <td class="align-middle">
                                    <a href="{{route('pages.user', $subscriber)}}" class="d-flex align-items-center gap-2">
                                        <img class="rounded" src="{{$subscriber->avatar_url}}" alt="{{$subscriber->username}} avatar" style="width: 50px;">
                                        <span>{{$subscriber->username}}</span>
                                    </a>
                                </td>
                                <td class="align-middle">{{$subscriber->pivot->subscribe_at->diffForHumans()}}</td>
                                <td class="align-middle">
                                    {{trans_choice('subscribers', $subscriber->subscribers_count)}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-user-slash fa-2x"></i>
                            <h5 class="my-3">No subscriber yet</h5>
                            <small class="text-muted">Some description</small>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{route('user.subscribers')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See all
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-7">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-comment"></i>
                    <h5 class="card-title mb-0">
                        Last comments on my videos
                    </h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->videos_comments->count())
                        <table class="table">
                        <thead>
                        <tr>
                            <th>Video</th>
                            <th>Comment</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(auth()->user()->videos_comments->take(5) as $comment)
                            <tr>
                                <td class="d-flex gap-3 align-items-center align-middle">
                                    <a href="{{route('pages.video', $comment->video)}}">
                                        <img src="{{$comment->video->poster_url}}" alt="" style="width: 100px;">
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex gap-2">
                                        <a href="{{route('pages.user', $comment->user)}}">
                                            <img class="rounded" src="{{$comment->user->avatar_url}}" alt="{{$comment->user->username}} avatar" style="width: 50px;">
                                        </a>
                                        <div>
                                            <a href="{{route('pages.user', $comment->user)}}">{{$comment->user->username}}</a>
                                            <small class="text-muted d-block">{{$comment->content}}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <small>{{$comment->created_at->diffForHumans()}}</small>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-comment-slash fa-2x"></i>
                            <h5 class="my-3">No comments yet</h5>
                            <small class="text-muted">Some description</small>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{route('user.comments')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See all
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-thumbs-up"></i>
                    <h5 class="card-title mb-0">
                        Last interactions on my videos
                    </h5>
                </div>
                <div class="card-body">
                    @if(auth()->user()->videos_interactions->count())
                        <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Video</th>
                            <th scope="col">User</th>
                            <th scope="col">Interaction</th>
                            <th scope="col">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(auth()->user()->videos_interactions->take(5) as $interaction)
                            <tr>
                                <td class="align-middle">
                                    <a href="{{route('pages.video', $interaction->likeable->id)}}">
                                        <img src="{{$interaction->likeable->poster_url}}" alt="" style="width: 100px;height: 60px">
                                    </a>
                                </td>
                                <td class="align-middle">
                                    <a href="{{route('pages.user', $interaction->user->id)}}" class="d-flex align-items-center gap-2">
                                        <img class="rounded" src="{{$interaction->user->avatar_url}}" alt="{{$interaction->user->username}} avatar" style="width: 50px;">
                                        <span>{{$interaction->user->username}}</span>
                                    </a>
                                </td>
                                <td class="align-middle">
                                    @if($interaction->status)
                                        <i class="fa-solid fa-thumbs-up"></i>
                                    @else
                                        <i class="fa-solid fa-thumbs-down"></i>
                                    @endif
                                </td>
                                <td class="align-middle">{{$interaction->perform_at->diffForHumans()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-eye-slash fa-2x"></i>
                            <h5 class="my-3">No interactions yet</h5>
                            <small class="text-muted">Some description</small>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                            <i class="fa-solid fa-eye"></i>
                            See all
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
