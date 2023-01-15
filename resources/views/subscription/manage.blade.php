@extends('layouts.default')

@section('title', 'All subscriptions')

@section('content')
    <h1>All Subscriptions ({{$subscriptions->count()}})</h1>
    <hr>
    <div class="container">
        @forelse($subscriptions as $user)
            @include('search.user', ['user' => $user])
        @empty
            <div></div>
        @endif
    </div>
@endsection
