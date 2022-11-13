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
        <div class="admin-content d-flex overflow-hidden">
            <nav class="d-flex flex-column flex-shrink-0 bg-light border-end admin-sidebar fixed">
                @include('layouts.parts.sidebar', ['type' => 'user'])
               {{-- <ul class="nav nav-pills flex-column mb-auto text-center">
                    <li class="nav-item">
                        <a href="{{route('user.index')}}" class="nav-link rounded-0 " aria-current="page">
                            <i class="fa-solid fa-chart-line"></i>&nbsp;
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('user.videos.index')}}" class="nav-link rounded-0 bg-primary bg-opacity-50 text-white" aria-current="page">
                            <i class="fa-solid fa-video"></i>&nbsp;
                            Videos
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-pills flex-column text-center">
                    <li class="nav-item">
                        <a href="{{route('user.profile')}}" class="nav-link rounded-0 bg-success text-white border-top border-success" aria-current="page">
                            <i class="fa-solid fa-user-cog"></i>&nbsp;
                            My account
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{route('logout')}}" class="nav-link rounded-0 bg-danger text-white border-top border-danger" aria-current="page">
                            <i class="fa-solid fa-right-from-bracket"></i>&nbsp;
                           Logout
                        </a>
                    </li>
                </ul>--}}
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
