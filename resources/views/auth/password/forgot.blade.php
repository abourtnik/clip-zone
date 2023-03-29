@extends('layouts.default')

@section('title', 'Password forget')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-75 mt-5">
            <div class="col-12 col-sm-10 offset-sm-1 col-xxl-8 offset-xxl-2 border border-1 bg-light">
                <div class="row">
                    <div class="col-6 d-none d-xl-flex px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <img class="img-fluid" src="{{asset('images/pages/forgot.jpg')}}" alt="Forgot password">
                    </div>
                    <div class="col-12 col-xl-6 p-5">
                        @if (session('status'))
                            <div class="alert alert-success fade show" role="alert">
                                <strong>{!! session('status') !!}</strong>
                            </div>
                        @endif
                        <h1 class="h3 mb-5 fw-normal text-center">Password forget</h1>
                         <ul class="ps-0 mb-5">
                             <li class="d-flex align-items-center gap-3 mb-3">
                                 <div class="bg-info bg-opacity-75 rounded-circle text-white border border-1 border-info d-flex align-items-center justify-content-center fw-bold dot">1</div>
                                 <p class="mb-0">Enter the email address associated with your account below.</p>
                             </li>
                             <li class="d-flex align-items-center gap-3 mb-3">
                                 <div class="bg-info bg-opacity-75 rounded-circle text-white border border-1 border-info d-flex align-items-center justify-content-center fw-bold dot">2</div>
                                 <p class="mb-0">You will receive an email, click on the link to choose a new password.</p>
                             </li>
                             <li class="d-flex align-items-center gap-3 mb-3">
                                 <div class="bg-info bg-opacity-75 rounded-circle text-white border border-1 border-info d-flex align-items-center justify-content-center fw-bold dot">3</div>
                                 <p class="mb-0">After validating your new password, you are connected to your account !</p>
                             </li>
                         </ul>
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
    </div>
@endsection
