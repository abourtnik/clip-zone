@extends('layouts.default')

@section('content')
    <div class="d-flex justify-content-center align-items-center h-75">
        <div id="login-form" class="w-50 border p-4 bg-light">
            @if ($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif
            <h1 class="h3 mb-3 fw-normal text-center">Create Account</h1>
            <form method="POST" action="{{route('register.perform')}}">
                @csrf
                <div class="form-group form-floating mb-3">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Username" required autofocus value="{{ old('username') }}">
                    <label for="username">Username</label>
                </div>
                <div class="form-group form-floating mb-3">
                    <input type="text" class="form-control" name="email" id="email" placeholder="Email" required value="{{ old('email') }}">
                    <label for="email">Email</label>
                </div>
                <div class="form-group form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-group form-floating mb-3">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
                    <label for="password_confirmation">Confirm password</label>
                </div>
                <div class="form-check my-4">
                    <input class="form-check-input" type="checkbox" value="" id="cgu">
                    <label class="form-check-label" for="cgu">
                        I agree all statements in <a href="#">Terms of service</a>
                    </label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Sign Up</button>
                <div class="mt-4 mb-3 text-muted text-center d-flex justify-content-center">
                    <p class="mr-2">Already have an account ?</p>
                    <a href="{{route('login')}}" class="font-weight-bold">
                        <strong>Sign In</strong>
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
