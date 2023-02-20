<nav class="navbar navbar-expand-md bg-light border border-start-0 px-2 sticky-top" style="height: 56px">
    <div class="container-fluid" x-data="{search:false}">
        <div class="d-flex align-items-center gap-3" x-show.important="!search">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsive-sidebar">
                <span class="navbar-toggler-icon" style="width: 1.2em;height: 1.2em;"></span>
            </button>
            <a @class(['navbar-brand text-danger fw-bold d-md-none me-0 fs-6', 'navbar-brand-auth' => Auth::guest()]) href="{{route('pages.home')}}">
                {{config('app.name')}}
            </a>
        </div>
        <div class="d-flex align-items-center d-md-none gap-3" x-show.important="!search">
            <div class="d-flex gap-4 align-items-center" >
                <button @click="search = true" class="nav-link d-flex align-items-center bg-transparent">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                @auth
                    <a x-show.important="!search" class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                        <strong class="d-none d-lg-block">{{auth()->user()->username}}</strong>
                        <img style="width: 40px" class="rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                    </a>
                    <a x-show.important="!search" class="nav-link" href="{{route('logout')}}">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                @else
                    <div x-show.important="!search">
                        <a class="btn bg-transparent text-primary ps-0" href="{{route('login')}}">
                            Log In
                        </a>
                        <a class="btn btn-sm btn-primary" href="{{route('registration')}}">
                            <i class="fa-solid fa-user"></i>&nbsp;
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        <div class="d-flex w-100 gap-4 d-md-none" x-show.important="search">
            <button @click="search = false" class="nav-link d-flex align-items-center bg-transparent">
                <i  class="fa-solid fa-arrow-left"></i>
            </button>
            <search-bar class="w-100" query="{{request()->get('q')}}"></search-bar>
        </div>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a style="width: 240px" class="navbar-brand text-center text-danger fw-bold" href="{{route('pages.home')}}">
                    {{config('app.name')}}
                </a>
                <search-bar class="w-35" responsive query="{{request()->get('q')}}"></search-bar>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item ml-4 align-items-center d-none d-lg-flex">
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#video_create">
                                <i class="fa-solid fa-video-camera"></i>
                                <span class="d-none d-xl-inline">Create</span>
                            </button>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                                <strong class="d-none d-lg-block">{{auth()->user()->username}}</strong>
                                <img style="width: 42px" class="ml-4 rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                            </a>
                        </li>
                        @impersonating($guard = null)
                            <li class="nav-item mx-3 d-flex align-items-center">
                                <a class="nav-link" href="{{route('impersonate.leave')}}">
                                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                                </a>
                            </li>
                        @else
                            <li class="nav-item mx-3 d-flex align-items-center">
                                <a class="nav-link" href="{{route('logout')}}">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </a>
                            </li>
                        @endImpersonating
                    @else
                        <li class="nav-item">
                            <a class="btn bg-transparent text-primary" href="{{route('login')}}">
                                Log In
                            </a>
                        </li>
                        <li class="nav-item ml-1 d-flex align-items-center">
                            <a class="btn btn-sm btn-primary" href="{{route('registration')}}">
                                <i class="fa-solid fa-user"></i>&nbsp;
                               Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
