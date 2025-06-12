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
                <span>{{ __('Account settings') }}</span>
            </a>
            @if(auth()->user()->premium_subscription)
                <a class="list-group-item list-group-item-action py-3" href="{{ auth()->user()->premium_subscription->is_canceled ? route('user.edit') : auth()->user()->billingPortalUrl(route('user.edit'))}}">
                    <i style="width: 24px" class="fa-solid fa-dollar"></i>
                    <span>{{ __('Membership') }}</span>
                </a>
            @else
                <a class="list-group-item list-group-item-action list-group-item-warning py-3" href="{{route('pages.premium')}}">
                    <i style="width: 24px" class="fa-solid fa-star"></i>
                    <span>Premium</span>
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
            <theme-switch id="theme-switcher-account"/>
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
