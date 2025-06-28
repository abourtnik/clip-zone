@extends('layouts.default')

@section('title', 'Password reset')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-100 mt-5">
            <div class="col-10 offset-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                <div class="row">
                    <div class="col-6 d-none d-xl-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <img class="img-fluid" src="{{asset('images/pages/reset.jpg')}}" alt="Reset">
                    </div>
                    <div class="col-12 col-xl-6 py-5 px-3 px-sm-5">
                        <h1 class="h3 mb-5 fw-normal text-center">Password reset</h1>
                        <form method="POST" action="{{route('password.update')}}">
                            @csrf
                            <div class="form-group form-floating mb-3">
                                <input
                                    type="email"
                                    value="{{request()->get('email')}}"
                                    @class(['form-control', 'is-invalid' => $errors->has('email')])
                                    name="email"
                                    id="email"
                                    placeholder="Email"
                                    required
                                    readonly
                                >
                                <label for="email">Email</label>
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group form-floating mb-3">
                                <input type="password" @class(['form-control', 'is-invalid' => $errors->has('password')]) name="password" id="password" placeholder="New Password" required minlength="6">
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
                            <input type="hidden" name="token" value="{{$token}}">
                            <button class="w-100 btn btn-lg btn-primary" type="submit">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
