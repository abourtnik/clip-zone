@extends('layouts.default')

@section('title', 'Subscriptions')

@section('content')
    @auth
        @if(!$subscriptions->count())
            <div class="alert alert-success fw-bold">Begin with subscribe to your first channel</div>
            <div class="row gx-3 gy-3">
                @each('subscription.partials.user', $users, 'user')
            </div>
        @else
            @forelse($sorted_videos as $date => $videos)
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{\Carbon\Carbon::createFromFormat('Y-m-d',$date)->calendar(now(), ['sameDay' => '[Today]', 'lastDay' => '[Yesterday]', 'lastWeek' => '[] dddd', 'sameElse' => 'D MMMM'])}}</h5>
                    @if($loop->index === 0)
                        <a href="{{route('subscription.manage')}}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-users"></i>
                            Manage
                        </a>
                    @endif
                </div>
                <hr>
                <div class="row gx-3 gy-4">
                    @each('videos.card', $videos, 'video')
                </div>
            @empty
                <div class="alert alert-success">You subscribed to {{$subscriptions->count()}} channel(s) but they have no videos. <strong>Subscribe to more channels</strong></div>
                <div class="row gx-3 gy-3">
                    @each('subscription.partials.user', $users, 'user')
                </div>
            @endforelse
        @endif
    @else
        <div class="h-full">
            <div class="row mt-5">
                <div class="col-10 offset-1 border p-4 bg-light text-center bg-light">
                    <i class="fa-brands fa-youtube fa-7x mb-3"></i>
                    <h2>Don’t miss new videos</h2>
                    <p class="text-muted">Sign In to see updates from your favorite YouTube channels</p>
                    <a href="{{route('login')}}" class="btn btn-outline-primary rounded-5 text-uppercase">
                        Sign in
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
