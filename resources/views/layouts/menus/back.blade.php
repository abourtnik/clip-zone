<nav class="d-flex flex-column flex-shrink-0 bg-light border-end admin-sidebar fixed">
    <ul class="nav nav-pills flex-column {{ ($type === 'default') ? '' : 'mb-auto' }}  text-center">
        @foreach(config('menu.'.$type. '.top') as $menu)
            <x-sidebar-item route="{{route($menu['route'])}}">
                <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                <span>{{$menu['title']}}</span>
            </x-sidebar-item>
        @endforeach
    </ul>
    @if((config('menu.'.$type. '.bottom')))
        <ul class="nav nav-pills flex-column text-center">
            @foreach(config('menu.'.$type. '.bottom') as $menu)
                <x-sidebar-item route="{{route($menu['route'])}}">
                    <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                    <span>{{$menu['title']}}</span>
                </x-sidebar-item>
            @endforeach
        </ul>
    @endif
</nav>
