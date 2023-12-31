@extends('layouts.default')

@section('title', 'Contact')

@section('content')
    <div class="h-full">
        <div class="row align-items-center h-100 mt-5">
            <div class="col-10 offset-1 offset-sm-1 border border-1 bg-light">
                <div class="row">
                    <div class="col-6 d-none d-xl-flex flex-column px-0 border-end border-gray-200 d-flex justify-content-center align-items-center bg-white">
                        <img class="img-fluid" src="{{asset('images/pages/contact.png')}}" alt="Contact Us">
                    </div>
                    <div class="col-12 col-xl-6 py-5 px-3 px-sm-5">
                        <h1 class="h3 mb-5 fw-normal text-center">{{ __('Contact Us') }}</h1>
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>{!! session('success') !!}</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form method="POST" action="{{route('contact.contact')}}">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="name" class="form-label">{{ __('Your name') }}</label>
                            <input
                                type="text"
                                minlength="{{config('validation.contact.name.min')}}"
                                maxlength="{{config('validation.contact.name.max')}}"
                                @class(['form-control', 'is-invalid' => $errors->has('name')])
                                name="name"
                                id="name"
                                required
                                autofocus
                                value="{{ old('name') }}"
                            >
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="email" class="form-label">{{ __('Your email') }}</label>
                            <input type="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) name="email" id="email" required value="{{ old('email') }}">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <input type="text" name="website" id="website" class="d-none">
                    </div>
                    <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.message.value.length">
                        <label for="message" class="form-label">{{ __('Your message') }}</label>
                        <textarea
                            @class(['form-control', 'is-invalid' => $errors->has('message')])
                            id="message"
                            rows="15"
                            name="message"
                            maxlength="{{config('validation.contact.message.max')}}"
                            x-ref="message"
                            @keyup="count = $refs.message.value.length"
                        >{{old('message')}}</textarea>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-text">
                                <span x-text="count"></span> / <span>{{config('validation.contact.message.max')}}</span>
                            </div>
                            @error('message')
                            <div @class(['invalid-feedback w-75 text-end', 'd-block' => $errors->has('message')])>
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <button class="w-100 btn btn btn-primary" type="submit">{{ __('Send') }}</button>
                </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
