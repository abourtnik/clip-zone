@extends('layouts.default')

@section('title', 'All playlists')

@section('content')
    <h4>All Playlists ({{$playlists->count()}})</h4>
    <hr>
    <div class="container">
        @forelse($playlists as $playlist)
            @include('search.playlist', ['playlist' => $playlist])
        @empty
            <div>no</div>
        @endforelse
    </div>
@endsection
