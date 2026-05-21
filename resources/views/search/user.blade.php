<article class="mb-3 card">
    <div class="position-relative d-flex flex-column flex-sm-row align-items-center px-6 py-4 gap-3 gap-sm-5 justify-content-between">
        <a href="{{$user->route}}" class="text-decoration-none d-flex flex-column flex-sm-row align-items-center gap-3 gap-sm-5">
            <img class="rounded-circle tw:w-[136px]" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
            <div class="text-center text-sm-start">
                <h6 class="mb-1">{{$user->username}}</h6>
                <small class="text-muted d-block mb-1">{{trans_choice('subscribers', $user->subscribers_count)}} • {{trans_choice('videos', $user->videos_count)}}</small>
                <small class="text-muted d-block">{{$user->short_description}}</small>
            </div>
            <span class="position-absolute tw:inset-0"></span>
        </a>
    </div>
</article>
