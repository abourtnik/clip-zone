@extends('layouts.user')

@section('content')
    @if($comments->total() || $filters)
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Comments</h2>
        </div>
        <hr>
        <form class="my-4 d-flex gap-3 align-items-end" method="GET">
            <div class="col">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col">
                <label for="video" class="form-label fw-bold">Video</label>
                <select name="video" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($videos as $video)
                        <option @if(($filters['video'] ?? null) === (string) $video->id) selected @endif value="{{$video->id}}">{{$video->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="user" class="form-label fw-bold">User</label>
                <select name="user" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($users as $user)
                        <option @if(($filters['user'] ?? null) === (string) $user->id) selected @endif value="{{$user->id}}">{{$user->username}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="comment_date_start" class="form-label fw-bold">Comment date start</label>
                <input type="datetime-local" name="date[]" class="form-control" id="comment_date_start" value="{{$filters['comment_date'][0] ?? null}}">
            </div>
            <div class="col">
                <label for="comment_date_end" class="form-label fw-bold">Comment date end</label>
                <input type="datetime-local" name="date[]" class="form-control" id="comment_date_end" value="{{$filters['comment_date'][1] ?? null}}">
            </div>
            <div class="col">
                <label for="replies" class="form-label fw-bold">Replies</label>
                <select name="replies" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach(['with', 'without'] as $option)
                        <option @if(($filters['replies'] ?? null) === $option) selected @endif value="{{$option}}">{{ucfirst($option)}} replies</option>
                    @endforeach
                </select>
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th class="w-25">Video</th>
                <th class="w-50">Comment</th>
                <th>Replies</th>
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
                                <img src="{{$comment->video->poster_url}}" alt="" style="width: 120px;height: 68px">
                            </a>
                            <div>{{Str::limit($comment->video->title, 100), '...'}}</div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="d-flex gap-2">
                            <a href="{{route('pages.user', $comment->user)}}">
                                <img class="rounded" src="{{$comment->user->avatar_url}}" alt="{{$comment->user->username}} avatar" style="width: 50px;">
                            </a>
                            <div>
                                <div class="d-flex align-items-center">
                                    <a class="text-sm text-decoration-none" href="{{route('pages.user', $comment->user)}}">{{$comment->user->username}}</a>
                                    @if($comment->user->is_subscribe_to_current_user)
                                        <small class="text-muted">&nbsp;• <span class="text-danger">Subscriber</span></small>
                                    @endif
                                    <small class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$comment->created_at->format('d F Y - H:i')}}">&nbsp;• {{$comment->created_at->diffForHumans()}}</small>
                                </div>
                                @if($comment->is_long)
                                    <div class="mt-1 d-block" x-data="{ open: false }">
                                        <template x-if="open">
                                            <small class="text-muted">
                                                {{$comment->content}}
                                            </small>
                                        </template>
                                        <template x-if="!open">
                                            <small class="text-muted">
                                                {{$comment->short_content}}
                                            </small>
                                        </template>
                                        <button @click="open=!open" class="text-primary text-sm bg-transparent d-block mt-1 ps-0" x-text="open ? 'Show less': 'Read more'"></button>
                                    </div>
                                @else
                                    <small class="text-muted d-block mt-1">{{$comment->content}}</small>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        @if($comment->replies_count)
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#comment_replies" data-id="{{$comment->id}}">
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
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#comment_replies" data-id="{{$comment->id}}">Reply</button>
                        <button class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
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
        {{ $comments->links() }}
        @include('users.comments.modals.replies')
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-comment-slash fa-2x"></i>
                    <h5 class="my-3">No comments yet</h5>
                    <div class="text-muted my-3">Upload more video for more comments</div>
                    <a href="{{route('user.videos.create')}}" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                        Upload new video
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
