@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@section('text')
    <p class="mb-1">We-re sorry, you are not authorized to perform this action</p>
    <p>Please go back</p>
@endsection
