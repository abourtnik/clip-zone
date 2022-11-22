@extends('layouts.default')

@section('content')
    <div class="d-flex justify-content-center align-items-center h-75">
        <div id="login-form" class="w-50 border p-4 bg-light">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{!! session('success') !!}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            <h1 class="h3 mb-3 fw-normal text-center">Login</h1>
            <form method="POST" action="{{route('login.perform')}}">
                @csrf
                <div class="form-group form-floating mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required autofocus value="{{ old('username') }}">
                    <label for="username">Email or Username</label>
                </div>
                <div class="form-group form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-group d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" id="remember" name="remember" class="form-check-input">
                        <label class="form-check-label" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a href="{{route('password.forgot')}}" class="text-muted text-decoration-none">Forgot password ?</a>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
                <div class="mt-4 mb-3 text-muted text-center d-flex justify-content-center">
                    <p class="mr-2">Do not have an account ?</p>
                    <a href="{{route('registration')}}" class="font-weight-bold">
                        <strong>Sign Up</strong>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
