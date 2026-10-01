@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Internal Server Error'))
@section('text')
    <p class="mb-1">{{ __('Sorry we had some technical problems during your last operation') }}</p>
    <p>{!! __('Try to refresh this page or <a href=":contact_url">contact us</a> if the problem persist', ['contact_url' => route('contact.show')]) !!}</p>
@endsection
