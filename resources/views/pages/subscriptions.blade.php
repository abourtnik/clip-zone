@extends('layouts.default')

@section('content')
    @auth
        @if(!$subscriptions)
            <div class="alert alert-success">Begin with subscribe to your first channel</div>
            <div class="row"></div>
                @forelse($users as $user)
                    <div class="col-2 card">
                        <div class="card-body d-flex flex-column gap-2 align-items-center">
                            <img style="width: 80px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                            <strong>{{$user->username}}</strong>
                            <div class="text-muted">{{trans_choice('subscribers', $user->subscribers_count)}}</div>
                            <subscribe-button isSubscribe="false" user="{{$user->id}}"/>
                        </div>
                    </div>
                @empty
                    <p>No users to subscribe</p>
                @endforelse
        @else
            @forelse($sorted_videos as $date => $videos)
                <h5>{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
                <div class="row">
                    @each('videos.card', $videos, 'video')
                </div>
            @empty
                <div class="alert alert-success">You subscribed to {{$subscriptions->count()}} channels but they have no videos. <strong>Subscribe to more channels</strong></div>
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
            @endforelse
        @endif
    @else
        <div class="d-flex justify-content-center align-items-center h-75">
            <div class="w-50 border p-4 bg-light text-center">
                <i class="fa-brands fa-youtube fa-7x mb-3"></i>
                <h2>Don’t miss new videos</h2>
                <p class="text-muted">Sign in to see updates from your favorite YouTube channels</p>
                <a href="{{route('login')}}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-user"></i>
                    Sign in
                </a>
            </div>
        </div>
    @endif
@endsection
