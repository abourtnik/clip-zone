<div @class(["offcanvas offcanvas-start fixed overflow-auto", 'show_sidebar' => $show_sidebar ]) id="responsive-sidebar" data-bs-keyboard="false" data-bs-backdrop="true" data-bs-scroll="true" aria-modal="true" role="dialog">
    <div @class(["offcanvas-header d-flex align-items-center bg-light border py-2", 'd-md-none' => $show_sidebar, 'd-flex gap-2' => Auth::user()?->is_premium, 'justify-content-between' => !Auth::user()?->is_premium])>
        <a
            href="{{route('pages.home')}}"
            @class(['offcanvas-title text-danger fw-bold text-decoration-none d-flex gap-2', 'h6' => Auth::user()?->is_premium, 'h5' => !Auth::user()?->is_premium])
        >
            <span>{{config('app.name')}}</span>
             @if(Auth::user()?->is_premium) <span class="text-warning">Premium</span> @endif
        </a>
        <div class="d-flex gap-2 align-items-center">
            @auth()
            <button class="btn btn-success btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#video_create">
                <i class="fa-solid fa-upload"></i>
                <span>{{__('Upload')}}</span>
            </button>
            @endauth
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
    <div class="offcanvas-body px-0 py-0">
        <nav class="flex-column flex-shrink-0 bg-white d-flex overflow-auto h-100">
            @foreach(config('menu.front.top') as $submenu)
                @unless($loop->index === 1 && !Auth::check())
                    <ul class="nav nav-pills flex-column text-center">
                        @foreach($submenu as $menu)
                            <x-sidebar-item route="{{route($menu['route'])}}" newTab="{{$menu['new_tab'] ?? false}}">
                                <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                                <span class="text-sm">{{ __($menu['title']) }}</span>
                            </x-sidebar-item>
                        @endforeach
                    </ul>
                    <hr class="w-90">
                @endunless
            @endforeach
            @if(Auth::check() && $favorite_playlists->count())
                <div class="d-flex align-items-center justify-content-between ps-3 pe-2 mb-2 text-sm">
                    <div class="fw-bold ">{{__('Playlists')}} ({{$favorite_playlists->count()}})</div>
                    <a class="text-decoration-none text-primary" href="{{route('playlist.manage')}}">{{__('See')}}</a>
                </div>
                <ul class="nav nav-pills flex-column text-center" x-data="{ open: false }">
                    @foreach($favorite_playlists->slice(0, 7) as $playlist)
                        <x-sidebar-item route="{{$playlist->route}}" class="justify-content-between">
                            <div class="d-flex align-items-center gap-4">
                                <i class="fa-solid fa-list"></i>
                                <span class="text-sm text-nowrap">{{Str::limit($playlist->title, 20)}}</span>
                            </div>
                        </x-sidebar-item>
                    @endforeach
                    @if($favorite_playlists->count() > 7)
                        <li class="nav-item">
                            <button @click="open = true" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" x-show.important="!open" role="button">
                                <i class="fa-solid fa-chevron-down"></i>
                                <small>{{ __('Show'). ' ' .trans_choice('elements', $favorite_playlists->count() - 7) }}</small>
                            </button>
                        </li>
                        <div x-show="open">
                            @foreach($favorite_playlists->slice(7) as $playlist)
                                <x-sidebar-item route="{{$playlist->route}}" class="justify-content-between">
                                    <i class="fa-solid fa-list"></i>
                                    <span class="text-sm text-nowrap">{{Str::limit($playlist->title, 20)}}</span>
                                </x-sidebar-item>
                            @endforeach
                            <li class="nav-item">
                                <button @click="open = false" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" role="button">
                                    <i class="fa-solid fa-chevron-up"></i>
                                    <small>{{__('Show less')}}</small>
                                </button>
                            </li>
                        </div>
                    @endif
                </ul>
                <hr>
            @endif
            @if(Auth::check() && $subscriptions->count())
                <div class="d-flex align-items-center justify-content-between ps-3 pe-2 mb-2 text-sm">
                    <div class="fw-bold ">{{__('Subscriptions')}} ({{$subscriptions->count()}})</div>
                    <a class="text-decoration-none text-primary" href="{{route('subscription.manage')}}">{{__('See')}}</a>
                </div>
                <ul class="nav nav-pills flex-column text-center" x-data="{ open: false }">
                    @foreach($subscriptions->slice(0, 7) as $user)
                        <x-sidebar-item route="{{$user->route}}" class="justify-content-between">
                            <div class="d-flex align-items-center gap-3">
                                <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                <span class="text-sm text-nowrap">{{Str::limit($user->username, 20)}}</span>
                            </div>
                            @if($user->has_new_video)
                                <span class="bg-primary rounded-circle" style="width: 8px;height: 8px"></span>
                            @endif
                        </x-sidebar-item>
                    @endforeach
                    @if($subscriptions->count() > 7)
                        <li class="nav-item">
                            <button @click="open = true" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" x-show.important="!open" role="button">
                                <i class="fa-solid fa-chevron-down"></i>
                                <small>{{ __('Show'). ' ' .trans_choice('elements', $subscriptions->count() - 7) }}</small>
                            </button>
                        </li>
                        <div x-show="open">
                            @foreach($subscriptions->slice(7) as $user)
                                <x-sidebar-item route="{{$user->route}}" class="justify-content-between">
                                    <div class="d-flex align-items-center gap-3">
                                        <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                        <span class="text-sm text-nowrap">{{Str::limit($user->username, 20)}}</span>
                                    </div>
                                    @if($user->new_videos)
                                        <span class="bg-primary rounded-circle" style="width: 8px;height: 8px"></span>
                                    @endif
                                </x-sidebar-item>
                            @endforeach
                            <li class="nav-item">
                                <button @click="open = false" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" role="button">
                                    <i class="fa-solid fa-chevron-up"></i>
                                    <small>{{ __('Show less') }}</small>
                                </button>
                            </li>
                        </div>
                    @endif
                    <x-sidebar-item route="{{route('subscription.discover')}}">
                        <i style="width: 24px" class="fa-solid fa-user-plus"></i>
                        <span class="text-sm">{{ __('Discover channels') }}</span>
                    </x-sidebar-item>
                </ul>
            @else
                <ul class="nav nav-pills flex-column text-center">
                    <x-sidebar-item route="{{route('subscription.discover')}}">
                        <i style="width: 24px" class="fa-solid fa-user-plus"></i>
                        <span class="text-sm">{{ __('Discover channels') }}</span>
                    </x-sidebar-item>
                </ul>
            @endif
            <hr class="w-90">
            <div class="fw-bold ps-3 mb-2">{{__('Explore')}}</div>
            <ul class="nav nav-pills flex-column mb-auto text-center">
                @foreach($categories as $category)
                    <x-sidebar-item route="{{route('pages.category', $category->slug)}}">
                        <i style="width: 24px" class="fa-solid fa-{{$category->icon}}"></i>
                        <span class="text-sm">{{__($category->title)}}</span>
                    </x-sidebar-item>
                @endforeach
            </ul>
            @if(!Auth::user()?->is_premium)
                <hr class="w-90">
                <ul class="nav nav-pills flex-column text-center">
                    <x-sidebar-item route="{{route('pages.premium')}}" class="fw-bold bg-warning" color="white">
                        <i style="width: 24px" class="fa-solid fa-star"></i>
                        <span class="text-sm">Premium</span>
                    </x-sidebar-item>
                </ul>
            @endif
            <hr class="w-90">
            <div class="d-flex align-items-center gap-2 justify-content-evenly">
                <form action="{{route('lang.update')}}" method="POST">
                    @csrf()
                    <select onchange="this.form.submit()" class="form-select form-select-sm text-sm" aria-label="Locale" name="locale">
                        @foreach(config('languages') as $lang => $info)
                            <option @selected(App::currentLocale() === $lang) value="{{$lang}}">{{$info['emoji']}} {{$info['label']}}</option>
                        @endforeach
                    </select>
                </form>
                <div class="theme-switch d-inline-block position-relative cursor-pointer">
                    <input type="checkbox" id="theme-switcher-global" class="theme-switcher" aria-label="Changer de thème">
                    <label for="theme-switcher-global">
                        <span class="switch"></span>
                        <svg class="icon icon-moon" fill="currentColor" width="800px" height="800px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/>
                        </svg>
                        <svg class="icon icon-sun" fill="#000000" width="800px" height="800px" viewBox="0 0 24 24" id="sun" data-name="Line Color" xmlns="http://www.w3.org/2000/svg">
                            <path  d="M12,3V4M5.64,5.64l.7.7M3,12H4m1.64,6.36.7-.7M12,21V20m6.36-1.64-.7-.7M21,12H20M18.36,5.64l-.7.7" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                            <circle cx="12" cy="12" r="4" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></circle>
                        </svg>
                    </label>
                </div>
            </div>
            <hr class="w-90">
            <div class="ps-4 pb-2">
                <a class="text-muted text-sm fw-bold text-decoration-none" href="{{route('pages.terms')}}">{{__('Terms of Service')}} •</a>
                <a class="text-muted text-sm fw-bold text-decoration-none" href="{{route('contact.show')}}">{{__('Contact')}}</a>
                <div class="mt-2 text-muted d-flex flex-column gap-1">
                    <small>{{__('Copyright')}} © {{now()->format('Y')}} {{config('app.name')}}</small>
                    <small>{{__('All Rights Reserved')}}</small>
                </div>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <a class="btn bg-dark text-white btn-sm mt-2" href="https://github.com/abourtnik/clip-zone" target="_blank">
                        <i class="fa-brands fa-github mr-2"></i>
                        <span>Github</span>
                    </a>
                    <a class="btn text-white btn-sm mt-2" href="https://www.paypal.com/donate/?hosted_button_id=P4KH8VMKM6XMJ" target="_blank" style="background-color: #0079C1">
                        <i class="fa-brands fa-paypal mr-2"></i>
                        <span>{{__('Donate')}}</span>
                    </a>
                </div>
                <a class="text-sm fw-bold text-decoration-none d-block mt-2" href="https://antonbourtnik.fr" target="_blank">Anton Bourtnik</a>
            </div>
        </nav>
    </div>
</div>
