@use('App\Filters\Forms\User\CommentFiltersForm')

@extends('layouts.user')

@section('title', 'Channel comments')

@section('content')
    @if($comments->total() || request()->all())
        <div class="d-flex justify-content-between align-items-center mt-3">
            <h2 class="mb-0">Your videos comments</h2>
        </div>
        <hr>
        {!! form(FormBuilder::create(CommentFiltersForm::class)) !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th class="w-40" style="min-width: 400px">Video</th>
                    <th class="w-50" style="min-width: 400px">Comment</th>
                    <th style="min-width: 140px">Replies</th>
                    <th style="min-width: 170px;">Interactions</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($comments as $comment)
                    <tr class="bg-light">
                        <td class="">
                            <div class="d-flex gap-3 align-items-center">
                                <a href="{{$comment->video->route}}">
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
