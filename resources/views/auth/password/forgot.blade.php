@extends('layouts.default')

@section('title', 'Forgot password')

@section('content')
    <div class="h-full">
        <div class="d-flex justify-content-center align-items-center h-75">
            <div id="login-form" class="w-50 border p-4 bg-light">
                <h1 class="h3 mb-3 fw-normal text-center">Password forget</h1>
                <form method="POST" action="{{route('password.email')}}">
                    @csrf
                    <div class="form-group form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email" placeholder="Email" required value="{{ old('email') }}">
                        <label for="email">Email</label>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Send me instructions</button>
                </form>
            </div>
        </div>
    </div>
@endsection
