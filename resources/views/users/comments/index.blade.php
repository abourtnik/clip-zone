@extends('layouts.user')

@section('title', 'Channel comments')

@section('content')
    @if($comments->total() || $filters)
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2 class="mb-0">My Comments</h2>
        </div>
        <hr>
        <form class="mb-4 row align-items-end gx-2 gy-2" method="GET">
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl col-xxl-3">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="video" class="form-label fw-bold">Video</label>
                <select name="video" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($videos as $video)
                        <option @selected(($filters['video'] ?? null) === (string) $video->id) value="{{$video->id}}">{{$video->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <search-model name="user" endpoint="{{route('search.users')}}" @isset($selectedUser)) value="{{$selectedUser}}" @endisset></search-model>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="date_start" class="form-label fw-bold">Comment date start</label>
                <input type="datetime-local" name="date_start" class="form-control" id="date_start" value="{{$filters['date_start'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="date_end" class="form-label fw-bold">Comment date end</label>
                <input type="datetime-local" name="date_end" class="form-control" id="date_end" value="{{$filters['date_end'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="replies" class="form-label fw-bold">Replies</label>
                <select name="replies" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach(['with', 'without'] as $option)
                        <option @selected(($filters['replies'] ?? null) === $option) value="{{$option}}">{{ucfirst($option)}} replies</option>
                    @endforeach
                </select>
            </div>
            <div class="btn-group col-auto">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th class="w-25">Video</th>
                    <th class="w-50" style="min-width: 370px">Comment</th>
                    <th style="min-width: 140px">Replies</th>
                    <th>Interactions</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($comments as $comment)
                    <tr class="bg-light">
                        <td class="">
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{route('video.show', $comment->video)}}">
                                    @include('users.videos.partials.thumbnail', ['video' => $comment->video])
                                </a>
                                <small>{{Str::limit($comment->video->title, 100), '...'}}</small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-2">
                                <a href="{{$comment->user->route}}">
                                    <img class="rounded" src="{{$comment->user->avatar_url}}" alt="{{$comment->user->username}} avatar" style="width: 50px;">
                                </a>
                                <div>
                                    <div class="d-flex align-items-center">
                                        <a class="text-sm text-decoration-none" href="{{$comment->user->route}}">{{$comment->user->username}}</a>
                                        @if($comment->user->is_subscribe_to_current_user)
                                            <small class="text-muted">&nbsp;• <span class="text-danger">Subscriber</span></small>
                                        @endif
                                        <small class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$comment->created_at->format('d F Y - H:i')}}">&nbsp;• {{$comment->created_at->diffForHumans()}}</small>
                                        @if($comment->is_updated)
                                            <small class="text-muted text-muted fw-semibold">&nbsp;• Modified</small>
                                        @endif
                                    </div>
                                    <x-expand-item>
                                        {{$comment->content}}
                                    </x-expand-item>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">
                            @if($comment->replies_count)
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#comment_replies" data-id="{{$comment->id}}" data-video="{{$comment->video->id}}">
                                    See replies ({{$comment->replies_count}})
                                </button>
                            @else
                                <div class="badge bg-secondary">
                                    No replies
                                </div>
                            @endif
                        </td>
                        <td class="align-middle">
                            @include('users.partials.interactions', ['item' => $comment])
                        </td>
                        <td class="align-middle">
                            <button
                                class="btn btn-sm btn-danger d-flex align-items-center gap-1"
                                data-bs-toggle="modal"
                                data-bs-target="#comment_delete"
                                data-route="{{route('user.comments.destroy', $comment)}}"
                                data-author="{{$comment->user->username}}"
                                data-replies-count="{{$comment->replies_count}}"
                            >
                                <i class="fa-solid fa-trash"></i>
                                Remove
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">No matching comments</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $comments->links() }}
        @include('users.comments.modals.replies')
        @include('users.comments.modals.delete')
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-comment-slash fa-2x"></i>
                    <h5 class="my-3">No comments yet</h5>
                    <div class="text-muted my-3">Upload more video for more comments</div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-plus"></i>
                        Upload new video
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
