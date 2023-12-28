@extends('layouts.admin')

@section('title', 'Comments')

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
                        <option @selected(($filters['video'] ?? null) === (string) $video->id) value="{{$video->id}}">{{$video->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="user" class="form-label fw-bold">User</label>
                <select name="user" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($users as $user)
                        <option @selected(($filters['user'] ?? null) === (string) $user->id) value="{{$user->id}}">{{$user->username}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="comment_date_start" class="form-label fw-bold">Comment date start</label>
                <input type="datetime-local" name="date[]" class="form-control" id="comment_date_start" value="{{$filters['date'][0] ?? null}}">
            </div>
            <div class="col">
                <label for="comment_date_end" class="form-label fw-bold">Comment date end</label>
                <input type="datetime-local" name="date[]" class="form-control" id="comment_date_end" value="{{$filters['date'][1] ?? null}}">
            </div>
            <div class="col">
                <label for="replies" class="form-label fw-bold">Replies</label>
                <select name="replies" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach(['with', 'without'] as $option)
                        <option @selected(($filters['replies'] ?? null) === $option) value="{{$option}}">{{ucfirst($option)}} replies</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="ban" class="form-label fw-bold">Banned</label>
                <select name="ban" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach(['banned' => 'Banned', 'not_banned' => 'Not banned'] as $id => $label)
                        <option @selected(($filters['ban'] ?? null) === $id) value="{{$id}}">{{$label}}</option>
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th class="w-25">Video</th>
                    <th class="w-50">Comment</th>
                    <th>Replies</th>
                    <th>Interactions</th>
                    <th>Ban</th>
                </tr>
                </thead>
                <tbody>
                @forelse($comments as $comment)
                    <tr class="bg-light">
                        <td class="">
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{$comment->video->route}}">
                                    <img src="{{$comment->video->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
                                </a>
                                <small>{{Str::limit($comment->video->title, 100), '...'}}</small>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-2">
                                <a href="{{$comment->user->route}}">
                                    <img class="rounded" src="{{$comment->user->avatar_url}}" alt="{{$comment->user->username}} avatar" style="width: 50px;">
                                </a>
                                <div class="d-flex flex-column gap-1">
                                    <div class="d-flex align-items-center">
                                        <a @class(['text-sm text-decoration-none', 'badge rounded-pill text-bg-secondary' => $comment->user->is($comment->video->user)]) href="{{$comment->user->route}}">{{$comment->user->username}}</a>
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
                            @if($comment->is_banned)
                                <div class="badge bg-warning">
                                    {{$comment->banned_at->diffForHumans()}}
                                </div>
                            @else
                                <button
                                    class="btn btn-danger btn-sm"
                                    title="Ban comment"
                                    data-bs-toggle="modal"
                                    data-bs-target="#ban_comment"
                                    data-route="{{route('admin.comments.ban', $comment)}}"
                                    data-username="{{$comment->user->username}}"
                                    data-avatar="{{$comment->user->avatar_url}}"
                                    data-date="{{$comment->created_at->diffForHumans()}}"
                                    data-content="{{$comment->content}}"
                                >
                                    <i class="fa-solid fa-ban"></i>
                                    Ban comment
                                </button>
                            @endif
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
        @include('admin.comments.modals.ban')
        @include('users.comments.modals.replies')
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
