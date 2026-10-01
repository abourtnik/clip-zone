@extends('errors::minimal')

@section('title', __('Service Unavailable'))
@section('code', '503')
@section('message', __(':app_name in under maintenance', ['app_name' => config('app.name')]))
@section('text')
    <p class="mb-1">{{ __(':app_name is undergoing maintenance. Sorry for the inconvenience', ['app_name' => config('app.name')]) }} </p>
    <p>{{__('Please go back later')}}</p>
@endsection
