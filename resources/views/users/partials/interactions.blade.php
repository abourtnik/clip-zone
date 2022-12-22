@if($item->interactions_count)
    <a href="#likes" data-bs-toggle="modal" data-bs-target="#video_likes" class="text-decoration-none text-black">
        <div class="progress m-auto mb-1" style="max-width: 160px;height: 5px">
            <div
                class="progress-bar bg-success"
                role="progressbar"
                aria-label="Likes ratio"
                style="width: {{$item->likes_ratio}}%;"
                aria-valuenow="{{$item->likes_ratio}}"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>
            <div
                class="progress-bar bg-danger"
                role="progressbar"
                aria-label="Dislikes ratio"
                style="width: {{$item->dislikes_ratio}}%"
                aria-valuenow="{{$item->dislikes_ratio}}"
                aria-valuemin="0"
                aria-valuemax="100">
            </div>
        </div>
        <div class="d-flex gap-2 justify-content-between">
            <small class="text-sm text-muted">{{$item->likes_ratio}}%</small>
            <div class="d-flex justify-content-between gap-2">
                <small class>
                    <i class="fa-regular fa-thumbs-up"></i>
                    {{$item->likes_count}}
                </small>
                <small>
                    <i class="fa-regular fa-thumbs-down"></i>
                    {{$item->dislikes_count}}
                </small>
            </div>
        </div>
    </a>
@else
    <div class="badge bg-secondary">
        No interactions
    </div>
@endif
