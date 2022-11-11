<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel - User</title>
    <script>
        window.User = {
            id : {{auth()->user()->id}}
        }
    </script>
    @vite(['resources/js/app.js'])
</head>
<body>
<main class="h-100 d-flex">
    <nav class="d-flex flex-column flex-shrink-0 p-3 bg-light border admin-sidebar">
        <a href="{{route('admin.index')}}" class="mb-3 mb-md-0 me-md-auto text-decoration-none link-dark">
            <span class="fs-4">{{env('APP_NAME')}}</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            @foreach(['articles' => 'newspaper', 'users' => 'user'] as $menu => $icon)
                <li class="nav-item">
                    <a href="{{route('admin.'.$menu.'.index')}}" class="nav-link {{ (request()->route()->action['as'] === 'admin.'.$menu.'.index') ? 'active' : 'text-black' }}" aria-current="page">
                        <i class="fa-solid fa-{{$icon}}"></i>&nbsp;
                        {{ucfirst($menu)}}
                    </a>
                </li>
            @endforeach
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle"  data-bs-toggle="dropdown" aria-expanded="false">
                <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                <strong>{{\Auth::user()->username}}</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="#">Profile</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Sign out</a></li>
            </ul>
        </div>
    </nav>
    <div class="w-100 admin-content">
        <nav class="navbar navbar-expand-lg bg-light border border-start-0">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    </ul>
                    <ul class="navbar-nav px-2">
                        <li class="nav-item dropdown">
                            <a class="nav-link position-relative" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa-solid fa-bell"></i>&nbsp;
                                @if(auth()->user()->unreadNotifications->count())
                                    <span id="notifications_count" class="position-absolute top-15 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{auth()->user()->unreadNotifications->count()}}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown" style="width: 375px">
                                <li>
                                    <h6 class="dropdown-header">10 Notification non lues</h6>
                                </li>
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action" aria-current="true">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <i class="fa-solid fa-info-circle text-primary" style="font-size: 25px"></i>&nbsp;
                                            <div>
                                                <p class="mb-1">Votre export des articles est disponible</p>
                                                <small class="text-muted">3 days ago</small>
                                            </div>
                                        </div>

                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <p class="mb-1">Votre export des articles est disponible</p>
                                        <small class="text-muted">3 days ago</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <p class="mb-1">Votre export des articles est disponible</p>
                                        <small class="text-muted">3 days ago</small>
                                    </a>
                                </div>
                                <li>
                                    <a class="dropdown-item text-secondary text-center" href="#">Voir toutes les notification</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown ml-4">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{\Auth::user()->username}}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Mon compte</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="{{route('logout')}}">Se deconnecter</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid mt-3">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! session('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @yield('content')
        </div>
        <div class="toast-container position-absolute p-3 top-0 start-50 translate-middle-x">
        </div>
    </div>
</main>

</body>
</html>
