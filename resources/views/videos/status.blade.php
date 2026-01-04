@if($video->is_failed)
    <button
        class="badge bg-danger"
        data-bs-toggle="popover"
        data-bs-placement="right"
        data-bs-title="The processing of your video failed."
        data-bs-trigger="focus"
        data-bs-html="true"
        data-bs-content="Try to re-upload your file, if the problem persist please contact your support.<hr><a target='_blank' href='/contact' class='btn btn-primary btn-sm'>Contact support</a>"
    >
        <i class="fa-solid fa-circle-exclamation"></i>&nbsp;
        {{ __('Failed') }}
    </button>
@elseif($video->is_uploading)
    <span class="badge bg-secondary">
        <i class="fa-solid fa-hourglass-half"></i>&nbsp;
        {{ __('Processing') }}
    </span>
@elseif($video->is_banned)
    <span class="badge bg-danger">
        <i class="fa-solid fa-ban"></i>&nbsp;
        {{ __('Banned') }} {{$video->banned_at->diffForHumans()}}
    </span>
@elseif($video->is_unlisted)
    <span class="badge bg-info">
        <i class="fa-solid fa-eye-slash"></i>&nbsp;
        {{ __('Unlisted') }}
    </span>
@elseif($video->is_planned)
    <span class="badge bg-warning">
        <i class="fa-solid fa-clock"></i>&nbsp;
        {{ __('Planned') }}
    </span>
@elseif($video->is_public)
    <span class="badge bg-success">
        <i class="fa-solid fa-earth-europe"></i>&nbsp;
        {{ __('Public') }}
    </span>
@elseif($video->is_draft)
    <span class="badge bg-secondary">
        <i class="fa-solid fa-file"></i>&nbsp;
        {{ __('Draft') }}
    </span>
@else
    <span class="badge bg-primary">
        <i class="fa-solid fa-lock"></i>&nbsp;
        {{ __('Private') }}
    </span>
@endif
