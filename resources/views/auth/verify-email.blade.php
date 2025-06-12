@extends('layouts.default')

@section('title', 'Email verification')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-100 mt-5">
            <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                <div class="row">
                    <div class="col-6 d-none d-xl-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <img class="img-fluid" src="{{asset('images/pages/register.png')}}" alt="Register">
                    </div>
                    <div class="col-12 col-xl-6 py-5 px-3 px-sm-5">
                        <h1 class="h3 mb-4 fw-normal text-center">{{__('Just One More Step !')}}</h1>
                        <hr>
                        <div class="d-flex flex-column justify-content-between h-70">
                            <p class="alert alert-info">{{ __('Thanks for signing up! Before getting started, could you verify your email address : ' . auth()->user()->email . ' by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>
                            <form
                                method="POST"
                                action="{{route('verification.send')}}"
                                x-data="{timer: {{ session()->get("verification.send|$user->id:next")?->isFuture() ? (int) now()->diffInSeconds(session()->get("verification.send|$user->id:next")) : 0}}}"
                                x-init="const t = setInterval(() => timer === 0 ? clearInterval(t) : timer--, 1000)"
                            >
                                @csrf
                                <button type="submit" class="w-100 btn btn-lg btn-primary d-flex align-items-center gap-1" :disabled="timer > 0">
                                    <span>{{ __('Resend verification email') }}</span>
                                    <div x-show="timer > 0">(<span x-text="timer"></span>)</div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
