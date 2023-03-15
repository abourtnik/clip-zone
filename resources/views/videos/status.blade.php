@if($video->is_public)
    <span class="badge bg-success">
        <i class="fa-solid fa-earth-europe"></i>&nbsp;
        Public
    </span>
@elseif($video->is_planned)
    <span class="badge bg-warning">
        <i class="fa-solid fa-clock"></i>&nbsp;
        Planned
    </span>
@elseif($video->is_unlisted)
    <span class="badge bg-info">
        <i class="fa-solid fa-eye-slash"></i>&nbsp;
        Unlisted
    </span>
@elseif($video->is_draft)
    <span class="badge bg-secondary">
        <i class="fa-solid fa-file"></i>&nbsp;
        Draft
    </span>
@elseif($video->is_banned)
    <span class="badge bg-primary">
        <i class="fa-solid fa-ban"></i>&nbsp;
        Banned {{$video->banned_at->diffForHumans()}}
    </span>
@else
    <span class="badge bg-danger">
        <i class="fa-solid fa-lock"></i>&nbsp;
        Private
    </span>
@endif
