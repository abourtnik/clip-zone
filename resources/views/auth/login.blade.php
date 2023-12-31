@extends('layouts.default')

@section('title', 'Login')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-75 mt-5">
            <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                <div class="row">
                    <div class="col-6 d-none d-xl-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <img class="img-fluid" src="{{asset('images/pages/login.png')}}" alt="Login">
                    </div>
                    <div class="col-12 col-xl-6 py-5 px-3 px-sm-5">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {!! session('error') !!}
                            </div>
                        @endif
                        @if (session('success'))
                            <div class="alert alert-success fade show" role="alert">
                                <strong>{!! session('success') !!}</strong>
                            </div>
                        @endif
                        <h1 class="h3 mb-5 fw-normal text-center">{{__('Welcome Back !')}}</h1>
                        <form method="POST" action="{{route('login.perform')}}">
                            @csrf
                            <div class="form-group form-floating mb-3 position-relative">
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" required autofocus value="{{ old('username') }}">
                                <label for="username">{{__('Email or Username')}}</label>
                            </div>
                            <div class="form-group form-floating mb-3" x-data="{show:false}">
                                <input :type="show ? 'text' : 'password'" class="form-control" id="password" name="password" placeholder="Password" required>
                                <button type="button" class="bg-transparent position-absolute top-50 start-100 translate-middle pe-5" @click="show=!show">
                                    <i x-show="!show" class="fa-solid fa-eye"></i>
                                    <i x-show="show" class="fa-solid fa-eye-slash"></i>
                                </button>
                                <label for="password">{{__('Password')}}</label>
                            </div>
                            <div class="form-group d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input type="checkbox" id="remember" name="remember" class="form-check-input">
                                    <label class="form-check-label" for="remember">
                                        {{__('Remember me')}}
                                    </label>
                                </div>
                            </div>
                            <button class="w-100 btn btn-lg btn-primary" type="submit">{{__('Sign In')}}</button>
                            <div class="mt-4 mb-3 text-muted text-center">
                                <div class="d-flex justify-content-center">
                                    <p class="mr-2">{{__('Do not have an account ?')}}</p>
                                    <a href="{{route('registration')}}" class="font-weight-bold text-decoration-none">
                                        <strong>{{__('Sign Up')}}</strong>
                                    </a>
                                </div>
                                <a href="{{route('password.forgot')}}" class="text-muted text-decoration-none">{{__('Forgot your password ?')}}</a>
                            </div>
                        </form>
                        <hr>
                        <a class="btn w-100 text-white text-center position-relative mb-2" href="{{route('oauth.connect', ['service' => 'facebook'])}}" style="background-color: #47639e">
                            <i class="fa-brands fa-facebook-f position-absolute top-50 left-5 translate-middle"></i>
                            <span>{{__('Sign In with Facebook')}}</span>
                        </a>
                        <a class="btn w-100 text-white text-center position-relative" href="{{route('oauth.connect', ['service' => 'google'])}}" style="background-color: #dd4b39">
                            <i class="fa-brands fa-google position-absolute top-50 left-5 translate-middle"></i>
                            <span>{{__('Sign In with Google')}}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
