<div class="offcanvas offcanvas-start fixed overflow-auto" id="responsive-sidebar" data-bs-keyboard="false" data-bs-backdrop="true" data-bs-scroll="true" aria-modal="true" role="dialog">
    <div class="offcanvas-header d-flex d-md-none">
        <h5 class="offcanvas-title text-danger fw-bold">{{config('app.name')}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body px-0 pt-0">
        <nav class="flex-column flex-shrink-0 bg-white d-flex overflow-auto">
            <ul class="nav nav-pills flex-column {{ ($type === 'default') ? '' : 'mb-auto' }}  text-center">
                @foreach(config('menu.'.$type. '.top') as $menu)
                    <x-sidebar-item route="{{route($menu['route'])}}">
                        <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                        <span class="text-sm">{{$menu['title']}}</span>
                    </x-sidebar-item>
                @endforeach
            </ul>
            @if((config('menu.'.$type. '.bottom')))
                <ul class="nav nav-pills flex-column text-center">
                    @foreach(config('menu.'.$type. '.bottom') as $menu)
                        <x-sidebar-item route="{{route($menu['route'])}}">
                            <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                            <span class="text-sm">{{$menu['title']}}</span>
                        </x-sidebar-item>
                    @endforeach
                </ul>
            @endif
        </nav>
    </div>
</div>
