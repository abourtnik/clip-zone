@extends('layouts.default')

@section('title', 'Discover new channels')

@section('content')
    @if($users->count())
        <h3 class="mb-4">Discover new channels</h3>
        <div class="row gx-3 gy-3">
            @each('subscription.partials.user', $users, 'user')
        </div>
    @else
        <div class="h-full">
            <div class="row mt-5">
                <div class="col-10 offset-1 border p-4 bg-light text-center bg-light">
                    <i class="fa-solid fa-user-slash fa-7x mb-3"></i>
                    <h2>No user to subscribe</h2>
                    <p class="text-muted">Sorry, no users to subscribe are available yet. Please check back later for updates.</p>
                </div>
            </div>
        </div>
    @endif
@endsection
