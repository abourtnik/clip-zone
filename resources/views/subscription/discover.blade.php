@extends('layouts.default')

@section('title', 'Discover')

@section('content')
    @if($users)
        <h3 class="mb-4">Discover new channels</h3>
        <div class="row gx-3 gy-3">
            @each('subscription.partials.user', $users, 'user')
        </div>
    @else
        <div class="d-flex justify-content-center align-items-center h-75">
            <div class="w-50 border p-4 bg-light text-center">
                <i class="fa-solid fa-user-slash fa-7x mb-3"></i>
                <h2>No user to subscribe</h2>
            </div>
        </div>
    @endif
@endsection