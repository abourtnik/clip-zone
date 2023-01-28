<nav class="navbar navbar-expand-sm bg-light border border-start-0 px-2 sticky-top" style="height: 56px">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-3">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsive-sidebar">
                <span class="navbar-toggler-icon" style="width: 1.2em;height: 1.2em;"></span>
            </button>
            <a class="navbar-brand text-danger fw-bold d-sm-none me-0 fs-6" href="{{route('pages.home')}}">Youtube Clone</a>
        </div>
        <div class="d-flex align-items-center d-sm-none gap-3">
            <div class="d-flex gap-4 align-items-center">
                <button class="nav-link d-flex align-items-center bg-transparent">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                @auth
                    <a class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                        <strong class="d-none d-lg-block">{{auth()->user()->username}}</strong>
                        <img style="width: 40px" class="rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                    </a>
                    <a class="nav-link" href="{{route('logout')}}">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                @else
                    <a class="btn btn-outline-primary" href="{{route('login')}}">
                        <i class="fa-solid fa-user"></i>&nbsp;
                        Sign in
                    </a>
                @endauth
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a style="width: 240px" class="navbar-brand text-center text-danger fw-bold" href="{{route('pages.home')}}">
                    Youtube Clone
                </a>
                <search-bar class="d-flex w-30" query="{{request()->get('q')}}"></search-bar>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item ml-4 align-items-center d-none d-lg-flex">
                            <a class="btn btn-success" href="{{route('user.videos.create')}}">
                                <i class="fa-solid fa-video-camera"></i>
                                <span class="d-none d-xl-inline">Create</span>
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                                <strong class="d-none d-lg-block">{{auth()->user()->username}}</strong>
                                <img style="width: 42px" class="ml-4 rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                            </a>
                        </li>
                        <li class="nav-item mx-3 d-flex align-items-center">
                            <a class="nav-link" href="{{route('logout')}}">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </li>
                    @else
                        <li class="nav-item ml-4">
                            <a class="btn btn-outline-primary" href="{{route('login')}}">
                                <i class="fa-solid fa-user"></i>&nbsp;
                                Sign in
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
