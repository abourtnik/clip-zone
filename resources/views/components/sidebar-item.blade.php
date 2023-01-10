<li class="nav-item">
    <a
        href="{{$route}}"
        class="nav-link rounded-0 d-flex align-items-center gap-4 text-black {{ ( URL::current() === $route) ? 'bg-light-dark fw-bold ' : '' }}"
        aria-current="page"
    >
        {{$slot}}
    </a>
</li>
