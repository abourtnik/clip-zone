@extends('layouts.default')

@section('title', 'Subscriptions')

@section('class', 'px-0 px-sm-2')

@section('content')
    @auth
        @if(!$subscriptions->count())
            <div class="alert alert-success fw-bold">Begin with subscribe to your first channel</div>
            <div class="row gx-3 gy-3">
                @each('subscription.partials.user', $users, 'user')
            </div>
        @else
            @forelse($sorted_videos as $date => $videos)
                <div class="px-2 px-sm-0">
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
                </div>
                <div class="row gx-3 gy-4 row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5">
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
        <div class="col-12 col-md-10 offset-md-1">
            <div class="h-full card shadow">
                <div class="card-body">
                    <div class="text-center">
                        <i class="fa-brands fa-youtube fa-4x mb-3"></i>
                        <h5 class="my-3">Don’t miss new videos</h5>
                        <p class="text-muted">Sign In to see updates from your favorite YouTube channels</p>
                        <a href="{{route('login')}}" class="btn btn-outline-primary rounded-5 text-uppercase">
                            Sign in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
