<nav class="navbar navbar-expand-md bg-white border border-start-0 px-2 sticky-top header">
    <div class="container-fluid" x-data="{search:false}">
        <div class="d-flex align-items-center gap-3" x-show.important="!search">
            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#responsive-sidebar">
                <span class="navbar-toggler-icon" style="width: 1.2em;height: 1.2em;"></span>
            </button>
            <a @class(['navbar-brand text-danger fw-bold d-md-none me-0 fs-6', 'navbar-brand-auth' => Auth::guest()]) href="{{route('pages.home')}}">
                {{config('app.name')}}
            </a>
        </div>
        <div class="d-flex align-items-center d-md-none gap-3" x-show.important="!search">
            <div class="d-flex gap-4 align-items-center">
                <button @click="search = true" class="nav-link d-flex align-items-center bg-transparent">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                @auth
                    <div x-data="{unread_notifications: {{$unread_notifications}}}">
                        <button type="button" class="nav-link bg-transparent position-relative" data-bs-toggle="offcanvas" data-bs-target="#responsive-notifications">
                            <i class="fa-solid fa-bell"></i>
                            <span x-show="unread_notifications > 0" class="position-absolute top-10 start-100 translate-middle badge rounded-pill bg-danger text-sm">
                                    <span x-show="unread_notifications > 99">99 +</span>
                                    <span x-show="unread_notifications <= 99" x-text="unread_notifications"></span>
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                        </button>
                        <div class="offcanvas offcanvas-end" id="responsive-notifications">
                            <div class="offcanvas-header bg-light border d-flex justify-content-between align-items-center">
                                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Notifications <span x-show="unread_notifications > 0">(<span x-text="unread_notifications"></span>)</span></h5>
                                <div class="d-flex gap-3 align-items-center">
                                    <a x-show="unread_notifications > 0" class="btn-link text-primary text-decoration-none text-sm fw-bold" href="#">Mark all as read</a>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                            </div>
                            <div class="offcanvas-body px-0 pt-0 overflow-auto" style="min-height: 500px">
                                @if($notifications->count())
                                    <ul class="list-group list-group-flush overflow-auto">
                                        @foreach($notifications as $notification)
                                            <a href="{{$notification->url}}" x-data="{is_read: {{$notification->is_read ? 'true': 'false'}}}" class="text-decoration-none text-black list-group-item hover-primary d-flex align-items-center justify-content-between">
                                                <div class="w-75">
                                                    <p class="mb-0 text-sm">{!! $notification->message !!}</p>
                                                    <p class="text-muted text-sm mb-0 mt-2">{{$notification->created_at->diffForHumans()}}</p>
                                                </div>
                                                <span x-show="!is_read" class="bg-primary rounded-circle" style="width: 10px;height: 10px"></span>
                                                <button
                                                    x-show="!is_read"
                                                    @click="is_read = true;unread_notifications--"
                                                    class="bg-success bg-opacity-25 rounded-4 btn-sm ajax-button"
                                                    type="button"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Mark as read"
                                                >
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </a>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="d-flex flex-column justify-content-center align-items-center p-5">
                                        <i class="fa-solid fa-bell-slash fa-2x"></i>
                                        <h5 class="mt-3 fs-6">Your notifications live here</h5>
                                        <p class="text-muted text-center text-sm">Subscribe to your favorite channels to get notified about their latest videos.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-light border w-100 text-center py-2">
                                <a class="btn-link text-primary text-decoration-none text-sm fw-bold" href="{{route('user.notifications.index')}}">See All Notifications</a>
                            </div>
                        </div>
                    </div>
                    <a x-show.important="!search" class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                        <strong class="d-none d-lg-block">{{auth()->user()->username}}</strong>
                        <img style="width: 40px" class="rounded-circle" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                    </a>
                    <a x-show.important="!search" class="nav-link" href="{{route('logout')}}">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                @else
                    <div x-show.important="!search">
                        <a class="btn bg-transparent text-primary ps-0" href="{{route('login')}}">
                            Log In
                        </a>
                        <a class="btn btn-sm btn-primary" href="{{route('registration')}}">
                            <i class="fa-solid fa-user"></i>&nbsp;
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        <div class="d-flex w-100 gap-4 d-md-none" x-show.important="search">
            <button @click="search = false" class="nav-link d-flex align-items-center bg-transparent">
                <i  class="fa-solid fa-arrow-left"></i>
            </button>
            <search-bar class="w-100" query="{{request()->get('q')}}"></search-bar>
        </div>
        <div class="collapse navbar-collapse">
            <div class="d-flex justify-content-between align-items-center w-100">
                <a style="width: 240px" class="navbar-brand text-center text-danger fw-bold" href="{{route('pages.home')}}">
                    {{config('app.name')}}
                </a>
                <search-bar class="w-35" responsive query="{{request()->get('q')}}"></search-bar>
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item ml-4 align-items-center d-none d-lg-flex">
                            <button class="btn btn-success btn-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#video_create">
                                <i class="fa-solid fa-video-camera"></i>
                                <span class="d-none d-xl-inline">Create</span>
                            </button>
                        </li>
                        <site-notifications class="nav-item mx-4 d-flex align-items-center dropdown" initial="{{$json_notifications}}"></site-notifications>
                        <li class="nav-item">
                            <a class="nav-link d-flex align-items-center" href="{{route('user.index')}}">
                                <img style="width: 42px;height: 42px" class="rounded-circle border" src="{{auth()->user()->avatar_url}}" alt="{{auth()->user()->username}} avatar">
                            </a>
                        </li>
                        @impersonating($guard = null)
                            <li class="nav-item mx-3 d-flex align-items-center">
                                <a class="nav-link" href="{{route('impersonate.leave')}}">
                                    <i class="fa-solid fa-right-from-bracket text-danger"></i>
                                </a>
                            </li>
                        @else
                            <li class="nav-item mx-3 d-flex align-items-center">
                                <a class="nav-link" href="{{route('logout')}}">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                </a>
                            </li>
                        @endImpersonating
                    @else
                        <li class="nav-item">
                            <a class="btn bg-transparent text-primary" href="{{route('login')}}">
                                Log In
                            </a>
                        </li>
                        <li class="nav-item ml-1 d-flex align-items-center">
                            <a class="btn btn-sm btn-primary" href="{{route('registration')}}">
                                <i class="fa-solid fa-user"></i>&nbsp;
                               Register
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</nav>
