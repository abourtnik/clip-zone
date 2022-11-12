<nav class="navbar navbar-expand-lg bg-light border border-start-0 d-flex justify-content-between align-items-center px-2 sticky-top" style="height: 56px">
    <a style="width: 280px" class="navbar-brand text-center" href="{{route('pages.home')}}">
        Youtube Clone
    </a>
    <form class="d-flex w-25" role="search">
        @csrf
        <div class="input-group">
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" name="search">
            <button class="btn btn-outline-secondary" type="button">
                <i class="fa-solid fa-search"></i>&nbsp;
            </button>
        </div>
    </form>
    <ul class="navbar-nav">
        <li class="nav-item ml-4">
            @auth
            <a class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                <strong>{{auth()->user()->username}}</strong>
                <img style="width: 42px" class="ml-4 rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
            </a>
            @else
            <a class="btn btn-outline-primary" href="{{route('login')}}">
                <i class="fa-solid fa-user"></i>&nbsp;
                Se connecter
            </a>
            @endif
        </li>
    </ul>
</nav>
