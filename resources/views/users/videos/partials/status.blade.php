@if($video->is_active)
    <span class="badge bg-success">
        <i class="fa-solid fa-eye"></i>&nbsp;
        Public
    </span>
@elseif($video->is_planned)
    <span class="badge bg-warning">
        <i class="fa-solid fa-clock"></i>&nbsp;
        Planned
    </span>
@else
    <span class="badge bg-danger">
        <i class="fa-solid fa-eye-slash"></i>&nbsp;
        Private
    </span>
@endif
