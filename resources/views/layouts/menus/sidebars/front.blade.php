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
    <div class="offcanvas-body px-0 pt-0">
        <nav class="flex-column flex-shrink-0 bg-white d-flex overflow-auto h-100">
            @foreach(config('menu.front.top') as $submenu)
                @unless($loop->index === 1 && !Auth::check())
                    <ul class="nav nav-pills flex-column text-center">
                        @foreach($submenu as $menu)
                            <x-sidebar-item route="{{route($menu['route'])}}" newTab="{{$menu['new_tab'] ?? false}}">
                                <i style="width: 24px" class="fa-solid fa-{{$menu['icon']}}"></i>
                                <span class="text-sm">{{$menu['title']}}</span>
                            </x-sidebar-item>
                        @endforeach
                    </ul>
                    <hr class="w-90">
                @endunless
            @endforeach
            @if(Auth::check() && $favorite_playlists->count())
                <div class="d-flex align-items-center justify-content-between ps-3 pe-2 mb-2 text-sm">
                    <div class="fw-bold ">Playlists ({{$favorite_playlists->count()}})</div>
                    <a class="text-decoration-none text-primary" href="{{route('playlist.manage')}}">See</a>
                </div>
                <ul class="nav nav-pills flex-column text-center" x-data="{ open: false }">
                    @foreach($favorite_playlists->slice(0, 7) as $playlist)
                        <x-sidebar-item route="{{$playlist->route}}" class="justify-content-between">
                            <div class="d-flex align-items-center gap-4">
                                <i class="fa-solid fa-list"></i>
                                <span class="text-sm">{{Str::limit($playlist->title, 20)}}</span>
                            </div>
                        </x-sidebar-item>
                    @endforeach
                    @if($favorite_playlists->count() > 7)
                        <li class="nav-item">
                            <button @click="open = true" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" x-show.important="!open" role="button">
                                <i class="fa-solid fa-chevron-down"></i>
                                <small>Show {{$favorite_playlists->count() - 7}} more</small>
                            </button>
                        </li>
                        <div x-show="open">
                            @foreach($favorite_playlists->slice(7) as $playlist)
                                <x-sidebar-item route="{{$playlist->route}}" class="justify-content-between">
                                    <i class="fa-solid fa-list"></i>
                                    <span class="text-sm">{{Str::limit($playlist->title, 20)}}</span>
                                </x-sidebar-item>
                            @endforeach
                            <li class="nav-item">
                                <button @click="open = false" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" role="button">
                                    <i class="fa-solid fa-chevron-up"></i>
                                    <small>Show Less</small>
                                </button>
                            </li>
                        </div>
                    @endif
                </ul>
                <hr>
            @endif
            @if(Auth::check() && $subscriptions->count())
                <div class="d-flex align-items-center justify-content-between ps-3 pe-2 mb-2 text-sm">
                    <div class="fw-bold ">Subscriptions ({{$subscriptions->count()}})</div>
                    <a class="text-decoration-none text-primary" href="{{route('subscription.manage')}}">See</a>
                </div>
                <ul class="nav nav-pills flex-column text-center" x-data="{ open: false }">
                    @foreach($subscriptions->slice(0, 7) as $user)
                        <x-sidebar-item route="{{$user->route}}" class="justify-content-between">
                            <div class="d-flex align-items-center gap-4">
                                <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                <span class="text-sm">{{$user->username}}</span>
                            </div>
                            @if($user->new_videos)
                                <span class="bg-primary rounded-circle" style="width: 8px;height: 8px"></span>
                            @endif
                        </x-sidebar-item>
                    @endforeach
                    @if($subscriptions->count() > 7)
                        <li class="nav-item">
                            <button @click="open = true" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" x-show.important="!open" role="button">
                                <i class="fa-solid fa-chevron-down"></i>
                                <small>Show {{$subscriptions->count() - 7}} more</small>
                            </button>
                        </li>
                        <div x-show="open">
                            @foreach($subscriptions->slice(7) as $user)
                                <x-sidebar-item route="{{$user->route}}" class="justify-content-between">
                                    <div class="d-flex align-items-center gap-4">
                                        <img style="width: 24px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                                        <span class="text-sm">{{$user->username}}</span>
                                    </div>
                                    @if($user->new_videos)
                                        <span class="bg-primary rounded-circle" style="width: 8px;height: 8px"></span>
                                    @endif
                                </x-sidebar-item>
                            @endforeach
                            <li class="nav-item">
                                <button @click="open = false" class="nav-link text-primary fw-bold rounded-0 gap-4 w-100 d-flex align-items-center gap-4" role="button">
                                    <i class="fa-solid fa-chevron-up"></i>
                                    <small>Show Less</small>
                                </button>
                            </li>
                        </div>
                    @endif
                    <x-sidebar-item route="{{route('subscription.discover')}}">
                        <i style="width: 24px" class="fa-solid fa-user-plus"></i>
                        <span class="text-sm">Discover channels</span>
                    </x-sidebar-item>
                </ul>
            @else
                <ul class="nav nav-pills flex-column text-center">
                    <x-sidebar-item route="{{route('subscription.discover')}}">
                        <i style="width: 24px" class="fa-solid fa-user-plus"></i>
                        <span class="text-sm">Discover channels</span>
                    </x-sidebar-item>
                </ul>
            @endif
            <hr class="w-90">
            <div class="fw-bold ps-3 mb-2">Explore</div>
            <ul class="nav nav-pills flex-column mb-auto text-center">
                @foreach($categories as $category)
                    <x-sidebar-item route="{{route('pages.category', $category->slug)}}">
                        <i style="width: 24px" class="fa-solid fa-{{$category->icon}}"></i>
                        <span class="text-sm">{{$category->title}}</span>
                    </x-sidebar-item>
                @endforeach
            </ul>
            <div class="ps-4">
                <hr class="w-90">
                <a class="text-muted text-sm fw-bold text-decoration-none" href="{{route('pages.terms')}}">Terms of Service •</a>
                <a class="text-muted text-sm fw-bold text-decoration-none" href="{{route('contact.show')}}">Contact</a>
                <div class="d-flex align-items-center gap-2 mt-2">
                    <small class="text-muted">© {{now()->format('Y')}}</small>
                    <a class="text-sm fw-bold text-decoration-none" href="https://antonbourtnik.fr" target="_blank">Anton Bourtnik</a>
                </div>
                <a class="btn bg-dark text-white btn-sm mt-2" href="https://github.com/abourtnik/youtube-clone" target="_blank">
                    <i class="fa-brands fa-github mr-2"></i>
                    <span>Github</span>
                </a>
            </div>
        </nav>
    </div>
</div>
