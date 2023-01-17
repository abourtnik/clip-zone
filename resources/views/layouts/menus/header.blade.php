<nav class="navbar navbar-expand-sm bg-light border border-start-0 px-2 sticky-top" style="height: 56px">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a style="width: 240px" class="navbar-brand text-center text-danger fw-bold" href="{{route('pages.home')}}">
                    Youtube Clone
                </a>
                <search-bar class="d-flex w-25"></search-bar>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item ml-4 d-flex align-items-center">
                            <a class="btn btn-success" href="{{route('user.videos.create')}}">
                                <i class="fa-solid fa-video-camera"></i>
                                Create
                            </a>
                        </li>
                        <li class="nav-item ml-4">
                            <a class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                                <strong>{{auth()->user()->username}}</strong>
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
