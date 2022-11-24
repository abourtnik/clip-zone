<nav class="d-flex flex-column flex-shrink-0 bg-light border-end admin-sidebar fixed">
    <ul class="nav nav-pills flex-column mb-auto text-center">
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
    @endisset
    {{--<hr class="my-3 w-75 mx-auto">
    @auth
        <hr class="my-3 w-75 mx-auto">
        <div class="fw-bold ps-4 mb-2">Abonnements</div>
        <ul class="nav nav-pills flex-column mb-auto text-center">
            @foreach(auth()->user()->subscriptions as $user)
                <li class="nav-item">
                    <a href="{{route('pages.user', $user)}}" class="nav-link rounded-0 gap-4 {{ (request()->route()->action['as'] === 'pages.user' && Route::current()->user->is($user)) ? 'bg-light-dark fw-bold text-black' : 'text-black' }} d-flex align-items-center" aria-current="page">
                        <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                        <span class="">{{$user->username}}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endauth--}}
</nav>
