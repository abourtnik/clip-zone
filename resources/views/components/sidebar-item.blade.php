<li class="nav-item">
    <a
        {{ $attributes->class(['nav-link rounded-0 d-flex align-items-center gap-4 text-black', 'bg-light-dark fw-bold' => URL::current() === $route]) }}
        href="{{$route}}"
        aria-current="page"
    >
        {{$slot}}
    </a>
</li>
