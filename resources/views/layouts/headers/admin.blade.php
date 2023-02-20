<nav class="navbar navbar-expand-md bg-light border border-start-0 px-2 sticky-top" style="height: 56px">
    <div class="d-flex align-items-center justify-content-between w-100 d-md-none">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsive-sidebar">
            <span class="navbar-toggler-icon" style="width: 1.2em;height: 1.2em;"></span>
        </button>
        <a class="text-decoration-none text-danger fw-bold me-0 fs-6" href="{{route('admin.index')}}">
            {{config('app.name')}} Admin
        </a>
        <a class="nav-link" href="{{route('logout')}}">
            <i class="fa-solid fa-right-from-bracket"></i>
        </a>
    </div>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <div class="d-flex justify-content-between align-items-center w-100">
            <a style="width: 240px" class="navbar-brand text-center text-danger fw-bold" href="{{route('admin.index')}}">
                {{config('app.name')}} Admin
            </a>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('logout')}}">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
