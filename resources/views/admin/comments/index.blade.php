@use('App\Filters\Forms\Admin\CommentFiltersForm')

@extends('layouts.admin')

@section('title', 'Comments')

@section('content')
    @if($comments->total() || request()->all())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Comments</h2>
        </div>
        <hr>
        {!! form(FormBuilder::create(CommentFiltersForm::class)) !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th style="min-width: 400px">Video</th>
                    <th style="min-width: 420px">Comment</th>
                    <th>Replies</th>
                    <th style="min-width: 150px">Interactions</th>
                    <th style="min-width: 142px">Ban</th>
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
                            <div class="d-flex gap-3">
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
                                <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#comment_replies" data-id="{{$comment->id}}" data-video="{{$comment->video->uuid}}">
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
