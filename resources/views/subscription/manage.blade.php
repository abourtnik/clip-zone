@extends('layouts.default')

@section('title', 'All subscriptions')

@section('content')
    <h4>All Subscriptions ({{$subscriptions->count()}})</h4>
    <hr>
    <div class="container">
        @forelse($subscriptions as $user)
            @include('search.user', ['user' => $user])
        @empty
            <div></div>
        @endforelse
    </div>
@endsection
