<nav class="navbar navbar-expand-lg bg-light border border-start-0 d-flex justify-content-between align-items-center px-2 sticky-top" style="height: 56px">
    <a style="width: 280px" class="navbar-brand text-center" href="{{route('pages.home')}}">
        Youtube Clone
    </a>
    <search-bar class="d-flex w-25"></search-bar>
    <ul class="navbar-nav">
        @auth
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
                    Se connecter
                </a>
            </li>
        @endif
    </ul>
</nav>
