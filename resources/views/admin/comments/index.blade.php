@use('App\Filters\Forms\Admin\CommentFiltersForm')

@extends('layouts.admin')

@section('title', __('Comments'))

@section('content')
    @if($comments->total() || request()->all())
        <div x-data="{comment: {}}">
            <div class="d-flex justify-content-between align-items-center my-3">
                <h2>{{ __('Comments') }}</h2>
            </div>
            <hr>
            {!! form(FormBuilder::create(CommentFiltersForm::class)) !!}
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr style="border-top: 3px solid #0D6EFD;">
                        <th style="min-width: 400px">{{ __('Video') }}</th>
                        <th style="min-width: 500px">{{ __('Comment') }}</th>
                        <th>{{ __('Replies') }}</th>
                        <th style="min-width: 150px">{{ __('Interactions') }}</th>
                        <th style="min-width: 142px">{{ __('Actions') }}</th>
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
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <a @class(['text-sm text-decoration-none', 'badge rounded-pill text-bg-secondary' => $comment->user->is($comment->video->user)]) href="{{$comment->user->route}}">{{$comment->user->username}}</a>
                                            <span class="text-muted text-sm">•</span>
                                            <small class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$comment->created_at->translatedFormat('d F Y - H:i')}}">
                                                {{$comment->created_at->diffForHumans()}}
                                            </small>
                                            @if($comment->is_updated)
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="text-muted text-sm">•</span>
                                                    <span class="text-muted fw-semibold text-sm">
                                                         {{ __('Modified') }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if($comment->is_banned)
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="text-muted text-sm">•</span>
                                                    <span class="text-danger fw-semibold text-sm">
                                                         {{ __('Banned :date', ['date' => $comment->banned_at->diffForHumans()]) }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if($comment->trashed())
                                                <div class="d-flex align-items-center gap-1">
                                                    <span class="text-muted text-sm">•</span>
                                                    <span class="text-danger fw-semibold text-sm">
                                                         {{ __('Deleted :date', ['date' => $comment->deleted_at->diffForHumans()]) }}
                                                    </span>
                                                </div>
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
                                        {{ __('No replies') }}
                                    </div>
                                @endif
                            </td>
                            <td class="align-middle">
                                @include('users.partials.interactions', ['item' => $comment])
                            </td>
                            <td class="align-middle">
                                @can('ban', $comment)
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm d-flex gap-2 align-items-center"
                                        title="Ban comment"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ban_comment"
                                        @click="comment = $el.dataset"
                                        data-route="{{route('admin.comments.ban', $comment)}}"
                                        data-user-username="{{$comment->user->username}}"
                                        data-user-avatar="{{$comment->user->avatar_url}}"
                                        data-date="{{$comment->created_at->diffForHumans()}}"
                                        data-content="{{$comment->content}}"
                                    >
                                        <i class="fa-solid fa-ban"></i>
                                        <span>{{ __('Ban') }}</span>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                <i class="fa-solid fa-database fa-2x my-3"></i>
                                <p class="fw-bold">{{ __('No matching comments') }}</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $comments->links() }}
            @include('admin.comments.modals.ban')
            @include('users.comments.modals.replies')
        </div>
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-comment-slash fa-2x"></i>
                    <h5 class="my-3">{{ __('No comments yet') }}</h5>
                    <div class="text-muted my-3">{{ __('Upload more video for more comments') }}</div>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-plus"></i>
                        {{ __('Upload new video') }}
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
