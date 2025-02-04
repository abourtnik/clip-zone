@extends('layouts.default')

@section('title', 'All subscriptions')

@section('content')
    @if($subscriptions->count())
        <h4>All Subscriptions ({{$subscriptions->count()}})</h4>
        <hr>
    @endif
    <div class="container">
        @forelse($subscriptions as $user)
            <article class="mb-3 card">
                <div class="position-relative d-flex flex-column flex-sm-row align-items-center px-6 py-4 gap-3 gap-sm-5 justify-content-between">
                    <a href="{{$user->route}}" class="text-decoration-none d-flex flex-column flex-sm-row align-items-center gap-3 gap-sm-5">
                        <img style="width: 136px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                        <div class="text-center text-sm-start">
                            <h6 class="mb-1">{{$user->username}}</h6>
                            <small class="text-muted d-block mb-1">{{trans_choice('subscribers', $user->subscribers_count)}} • {{trans_choice('videos', $user->videos_count)}}</small>
                            <small class="text-muted d-block">{{$user->short_description}}</small>
                        </div>
                        <span style="position: absolute;inset: 0;"></span>
                    </a>
                    <subscribe-button
                        user="{{$user->id}}"
                        size="sm"
                        class="mb-2 position-relative"
                    />
                </div>
            </article>
        @empty
            <div class="col-12 col-md-10 offset-md-1">
                <div class="h-full card shadow">
                    <div class="card-body">
                        <div class="text-center">
                            <i class="fa-brands fa-youtube fa-4x mb-3"></i>
                            <h5 class="mb-3">Don’t miss new videos</h5>
                            <p class="text-muted">Begin with subscribe to your first channel</p>
                            <a href="{{route('subscription.discover')}}" class="btn btn-outline-primary rounded-5 text-uppercase">
                                Discover new channels
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
