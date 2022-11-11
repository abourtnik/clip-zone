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
                    @foreach(['home' => 'house', 'trend' => 'fire'] as $menu => $icon)
                        <li class="nav-item">
                            <a href="{{route('pages.'.$menu)}}" class="nav-link rounded-0 {{ (request()->route()->action['as'] === 'pages.'.$menu) ? 'bg-primary bg-opacity-50 text-white' : 'text-black' }}" aria-current="page">
                                <i class="fa-solid fa-{{$icon}}"></i>&nbsp;
                                {{ucfirst($menu)}}
                            </a>
                        </li>
                    @endforeach
                </ul>
                @auth
                    <hr>
                    <h5 class="text-center">Abonnements</h5>
                    <ul class="nav nav-pills flex-column mb-auto text-center">
                        @foreach(auth()->user()->subscriptions as $user)
                            <li class="nav-item">
                                <a href="{{route('pages.user', $user)}}" class="nav-link rounded-0 text-black d-flex align-items-center justify-content-center" aria-current="page">
                                    <img style="width: 40px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                    <span class="ml-4">{{$user->username}}</span>
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
