<nav @class(["navbar bg-white border border-start-0 px-2 sticky-top header", 'navbar-expand-md' => $show_sidebar])  x-data="{search:false}">
    <div class="container-fluid" x-show.important="!search">
        <div @class(["gap-3 d-flex align-items-center justify-content-start", "gap-md-0 justify-content-md-center" => $show_sidebar ])>
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsive-sidebar">
                <span class="navbar-toggler-icon" style="width: 1.2em;height: 1.2em;"></span>
            </button>
            <a @class(['navbar-brand text-danger fw-bold', 'is-premium' => Auth::user()?->is_premium, 'show_sidebar' => $show_sidebar]) href="{{route('pages.home')}}">
                {{config('app.name')}} @if(Auth::user()?->is_premium) <span class="text-warning">Premium</span> @endif
            </a>
        </div>
        <search-bar class="col-6 col-xl-5 col-xxl-4 d-none d-lg-block" responsive></search-bar>
        <ul @class(['navbar-nav flex-row d-flex align-items-center', 'gap-1' => Auth::guest(), 'gap-4' => Auth::check()]) href="{{route('pages.home')}}">
            <li class="nav-item d-block d-lg-none">
                <button @click="search = true" class="bg-transparent nav-link text-black">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </li>
            @auth
                <li class="nav-item align-items-center d-none d-lg-flex">
                    <button class="btn btn-success btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-upload"></i>
                        <span class="d-none d-xl-inline">{{__('Upload')}}</span>
                    </button>
                </li>
                <li class="nav-item align-items-center">
                    <button class="btn nav-link bg-transparent btn-sm d-flex align-items-center gap-2 position-relative" data-bs-toggle="offcanvas" data-bs-target="#notifications">
                        <i id="bell" class="fa-solid fa-bell"></i>
                        <span id="notifications_count" @class(['position-absolute top-10 translate-middle badge rounded-pill bg-danger text-sm', 'd-none' => !$unread_notifications]) style="left: 90%">
                            @if($unread_notifications > 99)
                                <span>99 +</span>
                            @else
                                <span>{{$unread_notifications}}</span>
                            @endif
                        </span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="bg-transparent px-0" role="button" data-bs-toggle="offcanvas" data-bs-target="#account">
                        <img width="40" height="40" class="rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                    </button>
                </li>
                @else
                    <li class="nav-item">
                        <a class="btn bg-transparent text-primary" href="{{route('login')}}">
                            {{ __('Sign In') }}
                        </a>
                    </li>
                    <li class="nav-item d-flex align-items-center">
                        <a class="btn btn-sm btn-primary d-flex gap-2 align-items-center" href="{{route('registration')}}">
                            <span>{{ __('Register') }}</span>
                        </a>
                    </li>
                @endauth
        </ul>
    </div>
    <div class="d-flex w-100 gap-3 px-2" x-show.important="search">
        <button @click="search = false" class="nav-link d-flex align-items-center bg-transparent">
            <i  class="fa-solid fa-arrow-left"></i>
        </button>
        <search-bar class="w-100"></search-bar>
    </div>
</nav>
