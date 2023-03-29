<article class="mb-3 bg-light-dark">
    <div class="position-relative d-flex flex-column flex-sm-row align-items-center px-6 py-4 gap-5 justify-content-between">
        <a href="{{$user->route}}" class="text-decoration-none d-flex align-items-center gap-5">
            <img style="width: 136px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
            <div>
                <h6 class="mb-1">{{$user->username}}</h6>
                <small class="text-muted d-block mb-3">{{trans_choice('subscribers', $user->subscribers_count)}} • {{trans_choice('videos', $user->videos_count)}}</small>
                <small class="text-muted d-block">{{$user->short_description}}</small>
            </div>
            <span style="position: absolute;inset: 0;"></span>
        </a>
        @if(Auth::check() && !Auth::user()->is($user))
            <subscribe-button
                @if(!Auth()->user()->isSubscribeTo($user)) is-subscribe @endif
                user="{{$user->id}}"
                class="position-relative"
            />
        @elseif (!Auth::check())
            <button
                type="button"
                class="btn btn-danger position-relative"
                data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-title="Want to subscribe to this channel?"
                data-bs-trigger="focus"
                data-bs-html="true"
                data-bs-content="Sign in to subscribe to this channel.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
            >
                Subscribe
            </button>
        @endauth
    </div>
</article>
