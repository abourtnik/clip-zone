@use('App\Filters\Forms\User\DashboardFiltersForm')

@extends('layouts.user')

@section('title', 'Channel dashboard')

@section('content')
    {!! form(FormBuilder::create(DashboardFiltersForm::class)) !!}
    <div class="row mb-4">
        <div class="col-12 col-sm-4 col-md-4 col-xl-3 col-xxl mb-4 mb-xl-0">
            <div class="card shadow border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">Videos</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-video fa-2x"></i>
                        <p class="card-text text-center fs-1">{{$user->videos_count}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4 col-md-4 col-xl-3 col-xxl mb-4 mb-xl-0">
            <div class="card shadow border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">Subscribers</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-user fa-2x"></i>
                        <p class="card-text text-center fs-1">{{$user->subscribers_count}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-4 col-md-4 col-xl-3 col-xxl mb-4 mb-xl-0">
            <div class="card shadow border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">Views</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-eye fa-2x"></i>
                        <p class="card-text text-center fs-1">{{$user->videos_views_count}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl-3 col-xxl mb-4 mb-sm-0">
            <div class="card shadow border-primary">
                <div class="card-body">
                    <h5 class="card-title text-center text-primary">Comments</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-4">
                        <i class="fa-solid fa-comment fa-2x"></i>
                        <p class="card-text text-center fs-1">{{$user->videos_comments_count}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-6 col-xl mt-0 mt-xl-4 mt-xxl-0">
            <div class="card shadow border-primary">
                <div class="card-body text-center">
                    <h5 class="card-title text-center text-primary">Interactions</h5>
                    <hr>
                    <div class="d-flex align-items-center justify-content-center gap-5">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-thumbs-up fa-2x"></i>
                            <p class="card-text text-center fs-1">{{$user->videos_likes_count}}</p>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="fa-solid fa-thumbs-down fa-2x"></i>
                            <p class="card-text text-center fs-1">{{$user->videos_dislikes_count}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-xl-7 mb-4 mb-xl-0">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-video"></i>
                    <h5 class="card-title mb-0">
                        Last uploaded videos
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->videos_count || request()->all())
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                            <tr>
                                <th scope="col" class="w-35" style="min-width: 250px">Video</th>
                                <th scope="col" class="w-15" >Status</th>
                                <th scope="col" class="w-10">Views</th>
                                <th scope="col" class="w-10">Comments</th>
                                <th scope="col" class="w-20">Interactions</th>
                                <th scope="col" class="w-10">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($user->videos as $video)
                                    <tr>
                                        <td class="align-middle d-flex gap-3 align-items-center">
                                            <a href="{{$video->route}}">
                                                @include('users.videos.partials.thumbnail', ['video' => $video])
                                            </a>
                                            <small>{{Str::limit($video->title, 30)}}</small>
                                        </td>
                                        <td class="align-middle">
                                            @include('videos.status')
                                        </td>
                                        <td class="align-middle">{{$video->views}}</td>
                                        <td class="align-middle">
                                            @if($video->comments_count)
                                                <a href="{{route('user.comments.index') .'?video='.$video->id}}" class="badge bg-info text-decoration-none">
                                                    {{trans_choice('comments', $video->comments_count)}}
                                                </a>
                                            @else
                                                <div class="badge bg-secondary">
                                                    No comments
                                                </div>
                                            @endif
                                        </td>
                                        <td class="align-middle">
                                            @include('users.partials.interactions', ['item' => $video])
                                        </td>
                                        <td class="align-middle text-center">
                                            @can('update', $video)
                                                @if($video->is_draft)
                                                    <a href="{{route('user.videos.create', $video)}}" title="Edit draft">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                @else
                                                    <a href="{{route('user.videos.edit', $video)}}" title="Edit video">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-light">
                                        <td colspan="7" class="text-center">
                                            <i class="fa-solid fa-video-slash fa-2x my-3"></i>
                                            <p class="fw-bold">No matching video for this period</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-upload fa-2x"></i>
                            <h5 class="my-3">Import your first video</h5>
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#video_create">
                                <i class="fa-solid fa-plus"></i>
                                Import
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-eye"></i>&nbsp;
                            See all videos
                        </a>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#video_create">
                            <i class="fa-solid fa-upload"></i>
                            Import new video
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-user"></i>
                    <h5 class="card-title mb-0">
                        Last subscribers
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->subscribers_count || request()->all())
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Channel</th>
                                <th scope="col" style="min-width: 100px;">Date</th>
                                <th scope="col">Subscribers</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($user->subscribers as $subscriber)
                                <tr>
                                    <td class="align-middle">
                                        <a href="{{$subscriber->route}}" class="d-flex align-items-center gap-2 text-decoration-none">
                                            <img class="rounded-circle" src="{{$subscriber->avatar_url}}" alt="{{$subscriber->username}} avatar" style="width: 45px;">
                                            <span>{{$subscriber->username}}</span>
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-sm" data-bs-toggle="tooltip" data-bs-title="{{$subscriber->pivot->subscribe_at->format('d F Y - H:i')}}">
                                            {{$subscriber->pivot->subscribe_at->diffForHumans()}}
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div class="text-sm">
                                            {{trans_choice('subscribers', $subscriber->subscribers_count)}}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr class="bg-light">
                                    <td colspan="7" class="text-center">
                                        <i class="fa-solid fa-user-slash fa-2x my-3"></i>
                                        <p class="fw-bold">No matching subscribers for this period</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-user-slash fa-2x"></i>
                            <h5 class="my-3">No subscriber yet</h5>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{route('user.subscribers')}}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-eye"></i>&nbsp;
                            See all subscribers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-xl-7 mb-4 mb-xl-0">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-comment"></i>
                    <h5 class="card-title mb-0">
                        Last comments on my videos
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->videos_comments_count || request()->all())
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col" class="w-20" >Video</th>
                                    <th scope="col" class="w-55" style="min-width: 260px">Comment</th>
                                    <th scope="col" class="w-10">Replies</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($user->videos_comments as $comment)
                                    <tr>
                                        <td class="align-start">
                                            <div class="d-flex gap-3 align-items-center">
                                                <a href="{{$comment->video->route}}">
                                                    @include('users.videos.partials.thumbnail', ['video' => $comment->video])
                                                </a>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex gap-3 align-items-center">
                                                <a href="{{$comment->user->route}}">
                                                    <img class="rounded-circle" src="{{$comment->user->avatar_url}}" alt="{{$comment->user->username}} avatar" style="width: 45px;">
                                                </a>
                                                <div>
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{$comment->user->route}}" class="text-decoration-none text-sm">{{$comment->user->username}}</a>
                                                        <small class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$comment->created_at->format('d F Y - H:i')}}">&nbsp;• {{$comment->created_at->diffForHumans()}}</small>
                                                    </div>
                                                    <x-expand-item max="180" class="d-block">
                                                        {{$comment->content}}
                                                    </x-expand-item>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            @if($comment->replies_count)
                                                <div class="badge bg-info">
                                                    {{trans_choice('replies', $comment->replies_count)}}
                                                </div>
                                            @else
                                                <div class="badge bg-secondary">
                                                    No replies
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-light">
                                        <td colspan="7" class="text-center">
                                            <i class="fa-solid fa-comment-slash fa-2x my-3"></i>
                                            <p class="fw-bold">No matching comments for this period</p>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-comment-slash fa-2x"></i>
                            <h5 class="my-3">No comments yet</h5>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex gap-2">
                        <a href="{{route('user.comments.index')}}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-eye"></i>&nbsp;
                            See all comments
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-5">
            <div class="card h-100">
                <div class="card-header d-flex align-items-center gap-3 p-3">
                    <i class="fa-solid fa-thumbs-up"></i>
                    <h5 class="card-title mb-0">
                        Last interactions on my videos
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->videos_interactions_count || request()->all())
                        <div class="table-responsive">
                            <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Video</th>
                                <th scope="col" style="min-width: 150px;">User</th>
                                <th scope="col">Interaction</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($user->videos_interactions as $interaction)
                                <tr>
                                    <td class="align-middle">
                                        <a href="{{$interaction->likeable->route}}">
                                            @include('users.videos.partials.thumbnail', ['video' => $interaction->likeable])
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{$interaction->user->route}}" class="d-flex align-items-center gap-2 text-decoration-none">
                                            <img class="rounded-circle" src="{{$interaction->user->avatar_url}}" alt="{{$interaction->user->username}} avatar" style="width: 45px;">
                                            <div class="d-flex flex-column text-sm">
                                                <span>{{$interaction->user->username}}</span>
                                                <span class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$interaction->perform_at->format('d F Y - H:i')}}">{{$interaction->perform_at->diffForHumans()}}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="align-middle">
                                        @if($interaction->status)
                                            <div class="badge bg-success">
                                                <i class="fa-solid fa-thumbs-up"></i>
                                            </div>
                                        @else
                                            <div class="badge bg-danger">
                                                <i class="fa-solid fa-thumbs-down"></i>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr class="bg-light">
                                        <td colspan="7" class="text-center">
                                            <i class="fa-solid fa-bell-slash fa-2x my-3"></i>
                                            <p class="fw-bold">No matching interactions for this period</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    @else
                        <div class="text-center my-3">
                            <i class="fa-solid fa-eye-slash fa-2x"></i>
                            <h5 class="my-3">No interactions yet</h5>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{route('user.videos.index')}}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-eye"></i>&nbsp;
                            See all interactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
