@extends('layouts.default')

@section('title', __('My Favorite Playlists'))

@section('content')
    @if($playlists->count())
        <h3 class="mb-4">{{ __('My Favorite Playlists')}}</h3>
    @endif
    <div class="container">
        @forelse($playlists as $playlist)
            @include('search.playlist', ['playlist' => $playlist])
        @empty
            <div class="col-12 col-md-10 offset-md-1">
                <div class="h-full card shadow">
                    <div class="card-body">
                        <div class="text-center">
                            <i class="fa-solid fa-list fa-4x mb-3"></i>
                            <h5 class="mb-3">{{ __('You haven\'t added any playlists to your favorites yet') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
