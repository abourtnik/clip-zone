<li class="nav-item">
    <a
        {{ $attributes->class(['nav-link hover-grey rounded-0 d-flex align-items-center gap-3', 'bg-light-dark fw-bold text-primary border-start border-5 border-primary' => URL::current() === $route, 'text-' . ($color ?? 'black')  => URL::current() !== $route]) }}
        href="{{$route}}"
        aria-current="page"
        @if($attributes->get('newTab', false)) target="_blank" @endif
    >
        {{$slot}}
    </a>
</li>
