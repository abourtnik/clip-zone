@extends('layouts.user')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>My Comments</h2>
    </div>
    <hr>
    <table class="table table-bordered table-striped">
        <thead>
        <tr style="border-top: 3px solid #0D6EFD;">
            <th>Video</th>
            <th>Comment</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($comments as $comment)
            <tr class="bg-light">
                <td class="d-flex gap-3 align-items-center">
                    <a href="{{route('pages.video', $comment->video)}}">
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
                            <a href="{{route('pages.user', $comment->user)}}">{{$comment->user->username}}</a>
                            <small class="text-muted d-block">{{$comment->content}}</small>
                        </div>
                    </div>
                </td>
                <td>{{$comment->created_at->format('d F Y H:i')}}</td>
                <td>
                    <button class="btn btn-sm btn-primary">Répondre</button>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $comments->links() }}
@endsection
