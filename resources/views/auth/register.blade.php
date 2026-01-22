@extends('layouts.default')

@section('title', 'Register')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-100 mt-5">
            <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                <div class="row">
                    <div class="col-6 d-none d-xl-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <img class="img-fluid" src="{{asset('images/pages/register.png')}}" alt="Register">
                    </div>
                    <div class="col-12 col-xl-6 py-5 px-3 px-sm-5">
                        @error('throttle')
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                        @enderror
                        <h1 class="h3 mb-5 fw-normal text-center">{{__('Create your Account !')}}</h1>
                        <form method="POST" action="{{route('registration.perform')}}">
                            @csrf
                            <div class="form-group form-floating mb-3">
                                <input
                                    type="text"
                                    @class(['form-control', 'is-invalid' => $errors->has('username')])
                                    name="username"
                                    id="username"
                                    placeholder="Username"
                                    required
                                    minlength="{{config('validation.user.username.min')}}"
                                    maxlength="{{config('validation.user.username.max')}}"
                                    value="{{ old('username') }}"
                                >
                                <label for="username">{{__('Username')}}</label>
                                @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group form-floating mb-3">
                                <input
                                    type="email"
                                    @class(['form-control', 'is-invalid' => $errors->has('email')])
                                    name="email"
                                    id="email"
                                    placeholder="Email"
                                    value="{{ old('email') }}"
                                    required
                                >
                                <label for="email">{{__('Email')}}</label>
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group form-floating mb-3">
                                <input
                                    type="password"
                                    @class(['form-control', 'is-invalid' => $errors->has('password')])
                                    id="password"
                                    name="password"
                                    placeholder="Password"
                                    minlength="{{config('validation.user.password.min')}}"
                                    required
                                >
                                <label for="password">{{__('Password')}}</label>
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group form-floating mb-3">
                                <input
                                    type="password"
                                    @class(['form-control', 'is-invalid' => $errors->has('password_confirmation')])
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Confirm password"
                                    minlength="{{config('validation.user.password.min')}}"
                                    required
                                >
                                <label for="password_confirmation">{{__('Confirm password')}}</label>
                                @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-check my-4">
                                <input @class(['form-check-input', 'is-invalid' => $errors->has('cgu')]) type="checkbox" id="cgu" name="cgu">
                                <label class="form-check-label" for="cgu">
                                    {!! __('registration.terms', ['link' => route('pages.terms')]) !!}
                                </label>
                                @error('cgu')
                                <div class="invalid-feedback">
                                    {{ __('You must agree with Terms of service before creating a account.') }}
                                </div>
                                @enderror
                            </div>
                            <button class="w-100 btn btn-lg btn-primary" type="submit">{{ __('Register') }}</button>
                            <div class="mt-4 mb-3 text-muted text-center d-flex justify-content-center flex-wrap mb-3 gap-1 gap-sm-0">
                                <span class="mr-2 text-body">{{ __('Already have an account ?') }}</span>
                                <a href="{{route('login')}}" class="font-weight-bold text-decoration-none">
                                    <strong>{{ __('Sign In') }}</strong>
                                </a>
                            </div>
                        </form>
                        <hr>
                        <a class="btn w-100 text-white text-center position-relative mb-2" href="{{route('oauth.connect', ['service' => 'facebook'])}}" style="background-color: #47639e">
                            <i class="fa-brands fa-facebook-f position-absolute top-50 left-5 translate-middle"></i>
                            <span>{{ __('Register with Facebook') }}</span>
                        </a>
                        <a class="btn w-100 text-white text-center position-relative" href="{{route('oauth.connect', ['service' => 'google'])}}" style="background-color: #dd4b39">
                            <i class="fa-brands fa-google position-absolute top-50 left-5 translate-middle"></i>
                            <span>{{ __('Register with Google') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
