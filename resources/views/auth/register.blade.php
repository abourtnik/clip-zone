@extends('layouts.default')

@section('title', 'Register')

@section('content')
    <div class="h-full">
        <div class="d-flex justify-content-center align-items-center h-75">
            <div id="register-form" class="w-50 border p-4 bg-light">
                <h1 class="h3 mb-3 fw-normal text-center">Create Account</h1>
                <form method="POST" action="{{route('registration.perform')}}">
                    @csrf
                    <div class="form-group form-floating mb-3">
                        <input
                            type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            name="username"
                            id="username"
                            placeholder="Username"
                            required
                            minlength="3"
                            value="{{ old('username') }}"
                        >
                        <label for="username">Username</label>
                        @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group form-floating mb-3">
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email"
                            id="email"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                        >
                        <label for="email">Email</label>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group form-floating mb-3">
                        <input
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            placeholder="Password"
                            minlength="6"
                            required
                        >
                        <label for="password">Password</label>
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group form-floating mb-3">
                        <input
                            type="password"
                            class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm password"
                            minlength="6"
                            required
                        >
                        <label for="password_confirmation">Confirm password</label>
                        @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-check my-4">
                        <input class="form-check-input @error('cgu') is-invalid @enderror" type="checkbox" id="cgu" name="cgu">
                        <label class="form-check-label" for="cgu">
                            I agree all statements in <a href="{{route('pages.terms')}}">Terms of service</a>
                        </label>
                        @error('cgu')
                        <div class="invalid-feedback">
                            You must agree with Terms of service before creating a account.
                        </div>
                        @enderror
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
    </div>
@endsection
