@extends('layouts.default')

@section('content')
    <h3 class="mb-4">Discover new channels</h3>
    <div class="row gx-3 gy-3">
        @forelse($users as $user)
            <div class="col-2">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-2 align-items-center">
                        <img style="width: 80px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                        <strong>{{$user->username}}</strong>
                        <div class="text-muted">{{trans_choice('subscribers', $user->subscribers_count)}}</div>
                        <subscribe-button isSubscribe="false" user="{{$user->id}}"/>
                    </div>
                </div>
            </div>
        @empty
            <p>No users to subscribe</p>
        @endforelse
    </div>
@endsection
