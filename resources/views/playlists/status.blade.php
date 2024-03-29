@if($playlist->is_public)
    <span class="badge bg-success">
        <i class="fa-solid fa-eye"></i>&nbsp;
        Public
    </span>
@elseif($playlist->is_unlisted)
    <span class="badge bg-info">
        <i class="fa-solid fa-eye-slash"></i>&nbsp;
        Unlisted
    </span>
@else
    <span class="badge bg-danger">
        <i class="fa-solid fa-eye-slash"></i>&nbsp;
        Private
    </span>
@endif
