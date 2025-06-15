@extends('layouts.user')

@section('title', 'Account settings')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <p class="fw-bold">Oups some fields are incorrect</p>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success fade show" role="alert">
            <strong>{!! session('status') !!}</strong>
        </div>
    @endif
    @include('users.profile.parts.information')
    @include('users.profile.parts.channel')
    @include('users.profile.parts.socials')
    @include('users.profile.parts.devices')
    @include('users.profile.parts.password')
    @include('users.profile.parts.delete')

    @include('users.profile.modals.delete_account', $user)
    @include('users.partials.crop')
@endsection
