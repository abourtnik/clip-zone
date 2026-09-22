<article class="mb-3 card">
    <div class="position-relative d-flex flex-column flex-sm-row align-items-center px-6 py-4 gap-3 gap-sm-5 justify-content-between">
        <a href="{{$user->route}}" class="text-decoration-none text-black d-flex flex-column flex-sm-row align-items-center gap-3 gap-sm-5">
            <image-loaded source="{{$user->avatar_url}}" title="{{$user->username}} avatar" class="rounded-circle tw:size-[136px]"></image-loaded>
            <div class="text-center text-sm-start">
                <div class="d-flex gap-1 align-items-center mb-1 justify-content-center justify-content-sm-start">
                    <h6 class="mb-0">{!! $user->formatted_username !!}</h6>
                    <small class="text-muted">{!! '@'.$user->formatted_slug !!}</small>
                </div>
                <small class="text-muted d-block mb-1">{{trans_choice('subscribers', $user->subscribers_count)}} • {{trans_choice('videos', $user->videos_count)}}</small>
                @if($user->website)
                    <small class="text-muted d-block mb-1">{!! $user->formatted_website !!}</small>
                @endif
                <small class="text-muted d-block">{!! $user->formatted_description !!}</small>
            </div>
            <span class="position-absolute tw:inset-0"></span>
        </a>
    </div>
</article>
