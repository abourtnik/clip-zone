<div class="d-flex mb-3 position-relative gap-5 align-items-center px-6 py-4" style="background-color: #f2f2f2;">
    <img style="width: 136px" class="rounded-circle" src="{{$user->avatar_url}}" alt="{{$user->username}} avatar">
    <div>
        <h5 class="mb-1">{{$user->username}}</h5>
        <small class="text-muted d-block mb-3">{{trans_choice('subscribers', $user->subscribers_count)}} • {{trans_choice('videos', $user->videos_count)}}</small>
        <small class="text-muted d-block">{{$user->description}}</small>
    </div>
    <button
        type="button"
        class="btn btn-danger"
        data-bs-toggle="popover"
        data-bs-placement="right"
        data-bs-title="Want to subscribe to this channel?"
        data-bs-trigger="focus"
        data-bs-html="true"
        data-bs-content="Sign in to subscribe to this channel.<hr><a href='/login' class='btn btn-primary btn-sm'>Sign in</a>"
    >
        Subscribe
    </button>
</div>
