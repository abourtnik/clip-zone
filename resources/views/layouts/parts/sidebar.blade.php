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
    @if((config('menu.'.$type. '.subscriptions')))
        @auth
            <hr class="my-3 w-75 mx-auto">
            <div class="fw-bold ps-4 mb-2">Subscriptions ({{auth()->user()->subscriptions()->count()}})</div>
            <ul class="nav nav-pills flex-column mb-auto text-center" x-data="{ open: false }">
                @foreach(auth()->user()->latest_subscriptions->slice(0, 7) as $user)
                    <li class="nav-item">
                        <a href="{{route('pages.user', $user)}}" class="nav-link rounded-0 gap-4 {{ (request()->route()->action['as'] === 'pages.user' && Route::current()->user->is($user)) ? 'bg-light-dark fw-bold text-black' : 'text-black' }} d-flex align-items-center" aria-current="page">
                            <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                            <span class="">{{$user->username}}</span>
                        </a>
                    </li>
                @endforeach
                @if(auth()->user()->subscriptions()->count() > 7)
                    <li class="nav-item">
                        <button @click="open = true" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" :class="{'d-none':open}" role="button">
                            <i class="fa-solid fa-chevron-down"></i>
                            <span>Show {{auth()->user()->subscriptions()->count() - 7}} more</span>
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
                                <span>Show Less</span>
                            </button>
                        </li>
                    </div>
                 @endif
            </ul>
        @endauth
    @endif
</nav>

