@extends('layouts.user')

@section('content')
    @if($comments->total())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Comments</h2>
        </div>
        <hr>
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th>Video</th>
                <th>Comment</th>
                <th>Replies</th>
                <th>Interactions</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($comments as $comment)
                <tr class="bg-light">
                    <td class="d-flex gap-3 align-items-center">
                        <a href="{{route('video.show', $comment->video)}}">
                            <img src="{{$comment->video->poster_url}}" alt="" style="width: 120px;height: 68px">
                        </a>
                        <div>{{Str::limit($comment->video->title, 100), '...'}}</div>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{route('pages.user', $comment->user)}}">
                                <img class="rounded" src="{{$comment->user->avatar_url}}" alt="{{$comment->user->username}} avatar" style="width: 50px;">
                            </a>
                            <div>
                                <div class="d-flex align-items-center">
                                    <a href="{{route('pages.user', $comment->user)}}">{{$comment->user->username}}</a>
                                    <small class="text-muted">&nbsp;• {{$comment->created_at->diffForHumans()}}</small>

                                </div>
                                <small class="text-muted d-block mt-1">{{$comment->content}}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($comment->replies_count)
                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#comment_replies">
                                See replies ({{$comment->replies_count}})
                            </button>
                        @else
                            <div class="badge bg-secondary">
                                No replies
                            </div>
                        @endif
                    </td>
                    <td>
                        @include('users.comments.partials.interactions')
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary">Reply</button>
                        <button class="btn btn-sm btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
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
