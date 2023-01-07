<nav class="d-flex flex-column flex-shrink-0 bg-light border-end admin-sidebar fixed">
    <ul class="nav nav-pills flex-column {{ ($type === 'default') ? '' : 'mb-auto' }}  text-center">
        @foreach(config('menu.'.$type. '.top') as $menu)
            <li class="nav-item">
                <a
                    href="{{route($menu['route'])}}"
                    class="nav-link rounded-0 d-flex align-items-center gap-4 {{ (request()->route()->action['as'] === $menu['route']) ? 'bg-light-dark fw-bold text-black' : 'text-black' }}"
                    aria-current="page"
                >
                    <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                    <span>{{$menu['title']}}</span>
                </a>
            </li>
        @endforeach
    </ul>
    @if((config('menu.'.$type. '.bottom')))
        <ul class="nav nav-pills flex-column text-center">
            @foreach(config('menu.'.$type. '.bottom') as $menu)
                <li class="nav-item">
                    <a
                        href="{{route($menu['route'])}}"
                        class="nav-link rounded-0 d-flex align-items-center gap-4 {{ (request()->route()->action['as'] === $menu['route']) ? 'bg-light-dark fw-bold text-black' : 'text-black' }}"
                        aria-current="page"
                    >
                        <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                        <span>{{$menu['title']}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</nav>

