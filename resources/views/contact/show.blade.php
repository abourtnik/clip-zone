@extends('layouts.default')

@section('title', 'Contact')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-100">
            <div class="col-12 col-sm-10 offset-sm-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-3 border p-4 bg-light">
                <h1 class="h3 mb-3 fw-normal text-center">Contact Us</h1>
                <p class="text-muted">Whether you have a question, concern, or feedback about our platform, we welcome your input and are here to help. Simply fill out the form on this page with your name, email address, and message, and we'll get back to you as soon as possible.</p>
                <hr>
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{!! session('success') !!}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form method="POST" action="{{route('contact.contact')}}">
                    @csrf
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Your name</label>
                            <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) name="name" id="name" required autofocus value="{{ old('name', 'test') }}">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col mb-3">
                            <label for="email" class="form-label">Your email</label>
                            <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) name="email" id="email" required value="{{ old('email', 'test@test.fr') }}">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.message.value.length">
                        <label for="message" class="form-label">Your message</label>
                        <textarea
                            @class(['form-control', 'is-invalid' => $errors->has('message')])
                            id="message"
                            rows="15"
                            name="message"
                            maxlength="5000"
                            x-ref="message"
                            @keyup="count = $refs.message.value.length"
                        >{{old('message', 'loremlorem')}}</textarea>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-text">
                                <span x-text="count"></span> / <span>5000</span>
                            </div>
                            @error('message')
                            <div @class(['invalid-feedback w-75 text-end', 'd-block' => $errors->has('message')])>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary" type="submit">Send</button>
                </form>
            </div>
        </div>
    </div>
@endsection
