<div class="offcanvas offcanvas-end" tabindex="-1" id="account" aria-labelledby="account" style="width: 350px">
    <div class="offcanvas-header bg-light border d-flex justify-content-between align-items-start py-2">
        <div class="d-flex gap-3 align-items-start">
            <img width="45" height="45" class="rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
            <div>
                <div class="text-black fs-5 text-wrap">{{auth()->user()->username}}</div>
                @if(auth()->user()->is_premium)
                    <div class="badge bg-warning">
                        <i class="fa-solid fa-star"></i>
                        <span>{{ __('Premium account')}}</span>
                    </div>
                @else
                    <div class="badge bg-secondary">{{ __('Standard account')}}</div>
                @endif
                <a class="d-block text-decoration-none mt-2" href="{{auth()->user()->route}}">{{ __('View your channel')}}</a>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body px-0 pt-0">
        <div class="list-group list-group-flush overflow-auto">
            <a class="list-group-item list-group-item-action py-3" href="{{route('user.videos.index')}}">
                <i style="width: 24px" class="fa-solid fa-video"></i>
                <span>{{ __('Manage videos') }}</span>
            </a>
            <a class="list-group-item list-group-item-action py-3" href="{{route('user.edit')}}">
                <i style="width: 24px" class="fa-solid fa-user-cog"></i>
                <span>{{ __('Channel settings') }}</span>
            </a>
            @if(!auth()->user()->is_premium)
                <a class="list-group-item list-group-item-action list-group-item-warning py-3" href="{{route('pages.premium')}}">
                    <i style="width: 24px" class="fa-solid fa-star"></i>
                    <span>Premium</span>
                </a>
            @elseif(auth()->user()->stripe_id)
                <a class="list-group-item list-group-item-action py-3" href="{{auth()->user()->billingPortalUrl(route('user.edit'))}}">
                    <i style="width: 24px" class="fa-solid fa-star"></i>
                    <span>{{ __('Manage my subscription') }}</span>
                </a>
            @endif
            @if(auth()->user()->is_admin)
                <a class="list-group-item list-group-item-action list-group-item-danger py-3" href="{{route('admin.index')}}">
                    <i style="width: 24px" class="fa-solid fa-toolbox"></i>
                    <span class="">Admin</span>
                </a>
            @endif
        </div>
    </div>
    <div class="list-group list-group-flush border-1 border-top overflow-auto">
        <div class="list-group-item d-flex align-items-center list-group-item d-flex align-items-center justify-content-between">
            <form class="w-75" action="{{route('lang.update')}}" method="POST">
                @csrf()
                <select onchange="this.form.submit()" class="form-select form-select-sm text-sm" aria-label="Locale" name="locale">
                    @foreach(config('languages') as $lang => $info)
                        <option @selected(App::currentLocale() === $lang) value="{{$lang}}">{{$info['emoji']}} {{$info['label']}}</option>
                    @endforeach
                </select>
            </form>
            <div class="theme-switch d-inline-block position-relative cursor-pointer">
                <input type="checkbox" id="theme-switcher-account" class="theme-switcher" aria-label="Changer de thème">
                <label for="theme-switcher-account">
                    <span class="switch"></span>
                    <svg class="icon icon-moon" fill="currentColor" width="800px" height="800px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z"/>
                    </svg>
                    <svg class="icon icon-sun" fill="#000000" width="800px" height="800px" viewBox="0 0 24 24" id="sun" data-name="Line Color" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12,3V4M5.64,5.64l.7.7M3,12H4m1.64,6.36.7-.7M12,21V20m6.36-1.64-.7-.7M21,12H20M18.36,5.64l-.7.7" style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></path>
                        <circle cx="12" cy="12" r="4" style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;"></circle>
                    </svg>
                </label>
            </div>
        </div>
        @impersonating($guard = null)
        <a class="text-danger list-group-item py-2" href="{{route('impersonate.leave')}}">
            <i style="width: 24px" class="fa-solid fa-right-from-bracket"></i>
            <span>{{ __('Leave') }}</span>
        </a>
        @else
        <a class="list-group-item py-2" href="{{route('logout')}}">
            <i style="width: 24px" class="fa-solid fa-right-from-bracket"></i>
            <span>{{ __('Logout') }}</span>
        </a>
        @endImpersonating
    </div>
</div>
