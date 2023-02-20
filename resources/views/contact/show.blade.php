@extends('layouts.default')

@section('title', 'Contact')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-100">
            <h1 class="h3 fw-normal text-center">Contact Us</h1>
            <div id="login-form" class="col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3 border p-4 bg-light">
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success fade show" role="alert">
                        <strong>{!! session('success') !!}</strong>
                    </div>
                @endif
                <form method="POST" action="{{route('contact.contact')}}">
                    @csrf
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Your name</label>
                            <input type="text" class="form-control" name="name" id="name" required autofocus value="{{ old('name') }}">
                        </div>
                        <div class="col mb-3">
                            <label for="email" class="form-label">Your email</label>
                            <input type="email" class="form-control" name="email" id="email" required autofocus value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.message.value.length">
                        <label for="message" class="form-label">Your message</label>
                        <textarea
                            class="form-control"
                            id="message"
                            rows="15"
                            name="message"
                            maxlength="5000"
                            x-ref="message"
                            @keyup="count = $refs.message.value.length"
                        >{{old('message')}}</textarea>
                        <div class="form-text">
                            <span x-text="count"></span> / <span>5000</span>
                        </div>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>
@endsection
