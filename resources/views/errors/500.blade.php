@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Internal Server Error'))
@section('text')
    <p class="mb-1">Sorry we had some technical problems during your last operation.</p>
    <p>Try to refresh this page or <a href="{{route('contact.show')}}">contact us</a> if the problem persist</p>
@endsection
