@extends('layouts.default')

@section('title', 'Discover')

@section('content')
    @if($users)
        <h3 class="mb-4">Discover new channels</h3>
        <div class="row gx-3 gy-3">
            @foreach($users as $user)
                <div class="col-2">
                    <a href="{{$user->route}}" class="card text-decoration-none">
                        <div class="card-body d-flex flex-column gap-2 align-items-center">
                            <img style="width: 80px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                            <strong class="text-black">{{$user->username}}</strong>
                            <div class="text-muted">{{trans_choice('subscribers', $user->subscribers_count)}}</div>
                            @auth
                                <subscribe-button isSubscribe="false" user="{{$user->id}}" size="sm" />
                            @else
                                <button
                                    type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="popover"
                                    data-bs-placement="right"
                                    data-bs-title="Want to subscribe to this channel ?"
                                    data-bs-trigger="focus"
                                    data-bs-html="true"
                                    data-bs-content="Sign in to subscribe to this channel.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
                                >
                                    Subscribe
                                </button>
                            @endauth
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="d-flex justify-content-center align-items-center h-75">
            <div class="w-50 border p-4 bg-light text-center">
                <i class="fa-solid fa-user-slash fa-7x mb-3"></i>
                <h2>No user to subscribe</h2>
            </div>
        </div>
    @endif
@endsection
