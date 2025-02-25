@extends('layouts.default')

@section('title', 'Subscriptions')

@section('class', 'px-3')

@section('content')
    @auth
        @if(!$subscription_count)
            <div class="alert alert-success fw-bold">{{ __('Begin with subscribe to your first channel') }}</div>
            <div class="row gx-3 gy-3">
                @each('subscription.partials.user', $users, 'user')
            </div>
        @else
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">{{ __('Latest') }}</h5>
                <a href="{{route('subscription.manage')}}" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-users"></i>
                    {{ __('Manage') }}
                </a>
            </div>
            <videos-area url="{{route('me.subscriptions-videos')}}" />
        @endif
    @else
        <div class="h-full">
            <div class="row align-items-center h-75 mt-5">
                <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                    <div class="row">
                        <div class="col-6 d-none d-lg-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                            <img class="img-fluid" src="{{asset('images/pages/subscriptions.png')}}" alt="Subscriptions">
                        </div>
                        <div class="col-12 col-lg-6 py-5 px-3 px-sm-5 d-flex align-items-center justify-content-center text-center">
                            <div>
                                <h1 class="h3 mb-3 fw-normal">{{ __('Don’t miss new videos') }}</h1>
                                <p class="text-muted">{{ __('Sign In to see updates from your favorite channels') }}</p>
                                <a href="{{route('login')}}" class="btn btn-outline-primary rounded-5 text-uppercase">
                                    {{ __('Sign In') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
