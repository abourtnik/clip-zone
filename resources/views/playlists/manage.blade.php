@extends('layouts.default')

@section('title', 'All playlists')

@section('content')
    @if($playlists->count())
    <h4>Favorite Playlists ({{$playlists->count()}})</h4>
    <hr>
    @endif
    <div class="container">
        @forelse($playlists as $playlist)
            @include('search.playlist', ['playlist' => $playlist])
        @empty
            <div class="col-12 col-md-10 offset-md-1">
                <div class="h-full card shadow">
                    <div class="card-body">
                        <div class="text-center">
                            <i class="fa-brands fa-youtube fa-4x mb-3"></i>
                            <h5 class="mb-3">Don’t miss new videos</h5>
                            <p class="text-muted">Begin with subscribe to your first playlist</p>
                            <a href="{{route('subscription.discover')}}" class="btn btn-outline-primary rounded-5 text-uppercase">
                                Discover new channels
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
