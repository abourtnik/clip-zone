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
                <div class="row gx-3 gy-4 row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 row-cols-xxl-5 mb-5">
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
            <div class="row align-items-center h-75 mt-5">
                <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                    <div class="row">
                        <div class="col-6 d-none d-lg-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                            <img class="img-fluid" src="{{asset('images/pages/subscriptions.jpg')}}" alt="Subscriptions">
                        </div>
                        <div class="col-12 col-lg-6 py-5 px-3 px-sm-5 d-flex align-items-center justify-content-center text-center">
                            <div>
                                <h1 class="h3 mb-3 fw-normal">Don’t miss new videos</h1>
                                <p class="text-muted">Sign In to see updates from your favorite channels</p>
                                <a href="{{route('login')}}" class="btn btn-outline-primary rounded-5 text-uppercase">
                                    Sign in
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
