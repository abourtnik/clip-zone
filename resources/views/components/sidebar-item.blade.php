<li class="nav-item">
    <a
        {{ $attributes->class(['nav-link tw:hover:bg-gray-200! tw:dark:hover:bg-blue-500! rounded-0 d-flex align-items-center gap-3', 'tw:bg-gray-200! tw:dark:bg-blue-500! fw-bold text-primary' => URL::current() === $route, 'text-' . ($color ?? 'black')  => URL::current() !== $route]) }}
        href="{{$route}}"
        aria-current="page"
        @if($attributes->get('newTab', false)) target="_blank" @endif
    >
        {{$slot}}
    </a>
</li>
