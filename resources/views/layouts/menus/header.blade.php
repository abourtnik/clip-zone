<nav class="navbar navbar-expand-md bg-light border border-start-0 px-2 sticky-top" style="height: 56px">
    <div class="container-fluid" x-data="{search:false}">
        <div class="d-flex align-items-center gap-3" x-show.important="!search">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsive-sidebar">
                <span class="navbar-toggler-icon" style="width: 1.2em;height: 1.2em;"></span>
            </button>
            <a class="navbar-brand text-danger fw-bold d-md-none me-0 fs-6" href="{{route('pages.home')}}">Youtube ClonR</a>
        </div>
        <div class="d-flex align-items-center d-md-none gap-3">
            <div class="d-flex gap-4 align-items-center" >
                <button x-show.important="!search" @click="search = true" class="nav-link d-flex align-items-center bg-transparent">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <button x-show.important="search" @click="search = false" class="nav-link d-flex align-items-center bg-transparent">
                    <i  class="fa-solid fa-arrow-left"></i>
                </button>
                <div x-show="search">
                    <div class="input-group">
                        <input class="form-control rounded-5 rounded-end radius-end-0" type="search" placeholder="Search" aria-label="Search" name="q">
                        <button class="btn btn-outline-secondary rounded-5 rounded-start radius-start-0 px-4" type="submit">
                            <svg color="currentColor" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 14px;">
                                <path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352c79.5 0 144-64.5 144-144s-64.5-144-144-144S64 128.5 64 208s64.5 144 144 144z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
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
        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a style="width: 240px" class="navbar-brand text-center text-danger fw-bold" href="{{route('pages.home')}}">
                    Youtube Clone
                </a>
                <search-bar class="d-flex w-30" query="{{request()->get('q')}}"></search-bar>
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
                        <li class="nav-item mx-3 d-flex align-items-center">
                            <a class="nav-link" href="{{route('logout')}}">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </a>
                        </li>
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
