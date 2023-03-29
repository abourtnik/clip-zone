<nav class="navbar navbar-expand-md bg-white border border-start-0 px-2 sticky-top header"  x-data="{search:false}">
    <div class="container-fluid" x-show.important="!search">
        <div class="gap-3 gap-md-0 d-flex align-items-center justify-content-start justify-content-md-center">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsive-sidebar">
                <span class="navbar-toggler-icon" style="width: 1.2em;height: 1.2em;"></span>
            </button>
            <a class="navbar-brand text-danger fw-bold text-md-center" href="{{route('pages.home')}}">
                {{config('app.name')}}
            </a>
        </div>
        <search-bar class="w-35 d-none d-md-block" responsive query="{{request()->get('q')}}"></search-bar>
        <ul @class(['navbar-nav flex-row d-flex align-items-center', 'gap-1' => Auth::guest(), 'gap-4 gap-md-3' => Auth::check()]) href="{{route('pages.home')}}">
            <li class="nav-item d-block d-md-none">
                <button @click="search = true" class="bg-transparent nav-link text-black mr-2">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </li>
            @auth
                <li class="nav-item align-items-center d-none d-lg-flex">
                    <button class="btn btn-success btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-video-camera"></i>
                        <span class="d-none d-xl-inline">Create</span>
                    </button>
                </li>
                <li class="nav-item align-items-center">
                    <button class="btn nav-link bg-transparent btn-sm d-flex align-items-center gap-2 position-relative" data-bs-toggle="offcanvas" data-bs-target="#notifications">
                        <i class="fa-solid fa-bell"></i>
                        @if($unread_notifications)
                            <span class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger text-sm">
                                @if($unread_notifications > 99)
                                    <span>99 +</span>
                                @else
                                    <span>{{$unread_notifications}}</span>
                                @endif
                            </span>
                        @endif
                    </button>
                </li>
                <li class="nav-item">
                    <a class="" href="{{route('user.index')}}">
                        <img style="width: 42px;height: 42px" class="rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                    </a>
                </li>
                @impersonating($guard = null)
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link" href="{{route('impersonate.leave')}}">
                            <i class="fa-solid fa-right-from-bracket text-danger"></i>
                        </a>
                    </li>
                @else
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link" href="{{route('logout')}}">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </li>
                @endImpersonating
                @else
                    <li class="nav-item">
                        <a class="btn bg-transparent text-primary" href="{{route('login')}}">
                            Sign In
                        </a>
                    </li>
                    <li class="nav-item ml-1 d-flex align-items-center">
                        <a class="btn btn-sm btn-primary d-flex gap-2 align-items-center" href="{{route('registration')}}">
                            <span>Register</span>
                        </a>
                    </li>
                @endauth
        </ul>
    </div>
    <div class="d-flex w-100 gap-3 px-2" x-show.important="search">
        <button @click="search = false" class="nav-link d-flex align-items-center bg-transparent">
            <i  class="fa-solid fa-arrow-left"></i>
        </button>
        <search-bar class="w-100" query="{{request()->get('q')}}"></search-bar>
    </div>
</nav>
