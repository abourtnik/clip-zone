<div class="offcanvas offcanvas-start fixed overflow-auto" id="responsive-sidebar" data-bs-keyboard="false" data-bs-backdrop="true" data-bs-scroll="true" aria-modal="true" role="dialog">
    <div class="offcanvas-header d-flex justify-content-between align-items-center d-md-none bg-light border py-2">
        <h5 class="offcanvas-title text-danger fw-bold">
            {{config('app.name')}}
        </h5>
        <div class="d-flex gap-2 align-items-center">
            <button class="btn btn-success btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#video_create">
                <i class="fa-solid fa-video-camera"></i>
                <span>Create</span>
            </button>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <div class="offcanvas-body px-0 pt-0 pb-0">
        <nav class="flex-column flex-shrink-0 bg-white d-flex overflow-auto h-100">
            <ul class="nav nav-pills flex-column mb-auto text-center">
                @foreach(config('menu.'.$type) as $menu)
                    <x-sidebar-item route="{{route($menu['route'])}}">
                        <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                        <span class="text-sm">{{$menu['title']}}</span>
                    </x-sidebar-item>
                @endforeach
            </ul>
            <ul class="nav nav-pills flex-column text-center">
                <li class="nav-item">
                    <a
                        @class(['nav-link hover-grey rounded-0 d-flex align-items-center gap-4 bg-primary bg-opacity-25 text-black', 'bg-light-dark fw-bold text-primary border-start border-5 border-primary' => URL::current() === route('user.edit')])
                        href="{{route('user.edit')}}"
                        aria-current="page"
                    >
                        <img style="width: 40px" class="rounded-circle" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                        <span class="text-sm">{{auth()->user()->username}}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
