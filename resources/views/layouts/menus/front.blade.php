<nav class="d-flex flex-column flex-shrink-0 bg-light border-end admin-sidebar fixed">
    @foreach(config('menu.front.top') as $submenu)
        @unless($loop->index === 1 && !Auth::check())
            <ul class="nav nav-pills flex-column text-center">
                @foreach($submenu as $menu)
                    <x-sidebar-item route="{{route($menu['route'])}}">
                        <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                        <span class="text-sm">{{$menu['title']}}</span>
                    </x-sidebar-item>
                @endforeach
            </ul>
            <hr class="w-90">
        @endunless
    @endforeach
    @unless(!Auth::check() && !Auth::user()?->subscriptions()->count())
        <div class="fw-bold ps-4 mb-2 text-sm">Subscriptions ({{auth()->user()->subscriptions()->count()}})</div>
        <ul class="nav nav-pills flex-column text-center" x-data="{ open: false }">
            @foreach(auth()->user()->latest_subscriptions->slice(0, 7) as $user)
                <x-sidebar-item route="{{route('pages.user', $user)}}">
                    <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                    <span class="text-sm">{{$user->username}}</span>
                </x-sidebar-item>
            @endforeach
            @if(auth()->user()->subscriptions()->count() > 7)
                <li class="nav-item">
                    <button @click="open = true" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" :class="{'d-none':open}" role="button">
                        <i class="fa-solid fa-chevron-down"></i>
                        <small>Show {{auth()->user()->subscriptions()->count() - 7}} more</small>
                    </button>
                </li>
                <div x-show="open">
                    @foreach(auth()->user()->latest_subscriptions->slice(7) as $user)
                        <li class="nav-item">
                            <a href="{{route('pages.user', $user)}}" class="nav-link rounded-0 gap-4 {{ (request()->route()->action['as'] === 'pages.user' && Route::current()->user->is($user)) ? 'bg-light-dark fw-bold text-black' : 'text-black' }} d-flex align-items-center" aria-current="page">
                                <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                <span class="">{{$user->username}}</span>
                            </a>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <button @click="open = false" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" role="button">
                            <i class="fa-solid fa-chevron-up"></i>
                            <small>Show Less</small>
                        </button>
                    </li>
                </div>
            @endif
            <x-sidebar-item route="{{route('pages.discover')}}">
                <i style="width: 24px" class="fa-solid fa-user-plus"></i>
                <span class="text-sm">Discover channels</span>
            </x-sidebar-item>
        </ul>
    @else
        <ul class="nav nav-pills flex-column text-center">
            <x-sidebar-item route="{{route('pages.discover')}}">
                <i style="width: 24px" class="fa-solid fa-user-plus"></i>
                <span class="text-sm">Discover channels</span>
            </x-sidebar-item>
        </ul>
   @endunless
    <hr class="w-90">
    <div class="fw-bold ps-4 mb-2">Explore</div>
    <ul class="nav nav-pills flex-column mb-auto text-center">
        @foreach($categories as $category)
            <x-sidebar-item route="{{route('pages.category', $category->slug)}}">
                <i style="width: 24px" class="fa-solid fa-{{$category->icon}}"></i>
                <span class="text-sm">{{$category->title}}</span>
            </x-sidebar-item>
        @endforeach
    </ul>
    <hr class="w-90">
    <div class="mb-3 ps-4">
        <a class="text-muted text-sm fw-bold text-decoration-none" href="#">Terms</a>
        <a class="text-muted text-sm fw-bold text-decoration-none" href="#">Privacy</a>
        <div class="d-flex align-items-center gap-2 mt-2">
            <small class="text-muted">© {{now()->format('Y')}}</small>
            <a class="text-sm fw-bold text-decoration-none" href="https://antonbourtnik.fr/">Anton Bourtnik</a>
        </div>
    </div>
</nav>
