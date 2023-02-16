@extends('layouts.default')

@section('title', 'Password forget')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-75">
            <div id="login-form" class="col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3 border p-4 bg-light">
                @if (session('status'))
                    <div class="alert alert-success fade show" role="alert">
                        <strong>{!! session('status') !!}</strong>
                    </div>
                @endif
                <h1 class="h3 mb-3 fw-normal text-center">Password forget</h1>
                <form method="POST" action="{{route('password.email')}}">
                    @csrf
                    <div class="form-group form-floating mb-3">
                        <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) name="email" id="email" placeholder="Email" required value="{{ old('email') }}">
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
