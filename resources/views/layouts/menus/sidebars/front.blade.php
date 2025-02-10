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
                        <span class="text-sm">{{config('app.name')}} Premium</span>
                    </x-sidebar-item>
                </ul>
            @endif
            <hr class="w-90">
            <div class="d-flex flex-column gap-3">
                <div class="d-flex gap-2 align-items-center justify-content-evenly">
                    <form action="{{route('lang.update')}}" method="POST" class="w-60">
                        @csrf()
                        <select onchange="this.form.submit()" class="form-select form-select-sm text-sm" aria-label="Locale" name="locale">
                            @foreach(config('languages') as $lang => $info)
                                <option @selected(App::currentLocale() === $lang) value="{{$lang}}">{{$info['emoji']}} {{$info['label']}}</option>
                            @endforeach
                        </select>
                    </form>
                    <theme-switch id="theme-switcher-global"/>
                </div>
                <video-autoplay-switch/>
            </div>
            <hr class="w-90">
            <div class="ps-3 pb-2">
                <a class="text-muted text-sm fw-bold text-decoration-none" href="{{route('pages.terms')}}">{{__('Terms of Service')}} •</a>
                <a class="text-muted text-sm fw-bold text-decoration-none" href="{{route('contact.show')}}">{{__('Contact')}}</a>
                <div class="mt-2 text-muted d-flex flex-column gap-1">
                    <small>{{__('Copyright')}} © {{now()->format('Y')}} {{config('app.name')}}</small>
                    <small>{{__('All Rights Reserved')}}</small>
                </div>
                <div class="d-flex flex-column gap-2 mt-3">
                    <div class="d-flex align-items-center gap-2">
                       <a class="btn bg-dark text-white btn-sm" href="https://github.com/abourtnik/clip-zone" target="_blank">
                            <i class="fa-brands fa-github mr-2"></i>
                            <span>Github</span>
                        </a>
                        <a class="btn text-white btn-sm" href="https://www.paypal.com/donate/?hosted_button_id=P4KH8VMKM6XMJ" target="_blank" style="background-color: #0079C1">
                            <i class="fa-brands fa-paypal mr-2"></i>
                            <span>{{__('Donate')}}</span>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a style="background-color: #FB3F4C;" class="btn text-white btn-sm" href="https://play.google.com/store/apps/details?id=com.abourtnik.clipzone" target="_blank">
                            <i class="fa-brands fa-google-play mr-2"></i>
                            <span>Play Store</span>
                        </a>
                    </div>
                </div>
                <a class="text-sm fw-bold text-decoration-none d-block mt-2" href="https://antonbourtnik.fr" target="_blank">Anton Bourtnik</a>
            </div>
        </nav>
    </div>
</div>
