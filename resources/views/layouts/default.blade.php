<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{env('APP_NAME')}}</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <main class="h-100 overflow-auto">
        @include('layouts.parts.header')
        <div class="admin-content d-flex">
            <nav class="d-flex flex-column flex-shrink-0 bg-light border-end admin-sidebar fixed">
                <ul class="nav nav-pills flex-column text-center">
                    @foreach(['home' => 'house', 'trend' => 'fire', 'subscriptions' => 'user-check'] as $menu => $icon)
                        <li class="nav-item">
                            <a href="{{route('pages.'.$menu)}}" class="nav-link rounded-0 d-flex align-items-center gap-4 {{ (request()->route()->action['as'] === 'pages.'.$menu) ? 'bg-light-dark fw-bold text-black' : 'text-black' }}" aria-current="page">
                                <i style="width: 24px" class="fa-solid fa-{{$icon}}"></i>
                                <span>{{ucfirst($menu)}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <hr class="my-3 w-75 mx-auto">
                <ul class="nav nav-pills flex-column text-center">
                    @foreach(['library' => 'bookmark', 'history' => 'clock-rotate-left', 'playlist' => 'clock'] as $menu => $icon)
                        <li class="nav-item">
                            <a href="{{route('pages.'.$menu)}}" class="nav-link rounded-0 d-flex align-items-center gap-4 {{ (request()->route()->action['as'] === 'pages.'.$menu) ? 'bg-light-dark fw-bold text-black' : 'text-black' }}" aria-current="page">
                                <i style="width: 24px" class="fa-solid fa-{{$icon}}"></i>
                                <span>{{ucfirst($menu)}}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @auth
                    <hr class="my-3 w-75 mx-auto">
                    <div class="fw-bold ps-4 mb-2">Abonnements</div>
                    <ul class="nav nav-pills flex-column mb-auto text-center">
                        @foreach(auth()->user()->subscriptions as $user)
                            <li class="nav-item">
                                <a href="{{route('pages.user', $user)}}" class="nav-link rounded-0 gap-4 {{ (request()->route()->action['as'] === 'pages.user' && Route::current()->user->is($user)) ? 'bg-light-dark fw-bold text-black' : 'text-black' }} d-flex align-items-center" aria-current="page">
                                    <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                    <span class="">{{$user->username}}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endauth
            </nav>
            <div class="container-fluid mt-3" style="margin-left: 280px">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {!! session('success') !!}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </main>
</body>
</html>
