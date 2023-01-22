<article class="col-sm-6 col-md-4 col-lg-3 col-xl-2">
    <div class="position-relative card">
        <a href="{{$user->route}}" class="text-decoration-none">
            <div class="card-body d-flex flex-column gap-1 align-items-center">
                <img style="width: 80px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
                <strong class="text-black">{{$user->username}}</strong>
                <div class="text-muted">{{trans_choice('subscribers', $user->subscribers_count)}}</div>
            </div>
            <span style="position: absolute;inset: 0;"></span>
        </a>
        @auth
            <subscribe-button
                @if(!Auth()->user()->isSubscribeTo($user)) is-subscribe @endif
                user="{{$user->id}}"
                size="sm"
                class="mx-auto mb-2 position-relative"
            />
        @else
            <button
                type="button"
                class="btn btn-danger btn-sm position-relative mx-auto mb-2"
                data-bs-toggle="popover"
                data-bs-placement="right"
                data-bs-title="Want to subscribe to this channel ?"
                data-bs-trigger="focus"
                data-bs-html="true"
                data-bs-content="Sign in to subscribe to this channel.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
            >
                Subscribe
            </button>
        @endauth
    </div>
</article>
