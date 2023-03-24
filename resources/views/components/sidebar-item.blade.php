<li class="nav-item">
    <a
        {{ $attributes->class(['nav-link hover-grey rounded-0 d-flex align-items-center gap-4', 'bg-light-dark fw-bold text-primary border-start border-5 border-primary' => URL::current() === $route, 'text-black' => URL::current() !== $route]) }}
        href="{{$route}}"
        aria-current="page"
    >
        {{$slot}}
    </a>
</li>
