@extends('errors.minimal')

@section('title', __('Page Not Found'))
@section('code', '404')
@section('message', __('Oops! Page Not found'))
@section('text')
    <p class="mb-1">{{ __('We-re sorry, the resource you requested could not be found') }}</p>
    <p>{{ __('Please go back to the homepage') }}</p>
@endsection
