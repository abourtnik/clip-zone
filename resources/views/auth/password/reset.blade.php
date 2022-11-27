@extends('layouts.default')

@section('content')
    <div class="d-flex justify-content-center align-items-center h-75">
        <div class="w-50 border p-4 bg-light">
            @if ($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif
            <h1 class="h3 mb-3 fw-normal text-center">Password reset</h1>
            <form method="POST" action="{{route('password.update')}}">
                @csrf
                <div class="form-group form-floating mb-3">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="New Password" required minlength="6">
                    <label for="password">New Password</label>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-group form-floating mb-3">
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm New Password" required minlength="6">
                    <label for="password_confirmation">Confirm New Password</label>
                </div>
                <button class="w-100 btn btn-lg btn-primary" type="submit">Reset Password</button>
            </form>
        </div>
    </div>
@endsection
