<a href="#likes" data-bs-toggle="modal" data-bs-target="#video_likes" class="text-decoration-none text-black">
    <div class="d-flex gap-2 justify-content-center mb-3">
        <div>
            <i class="fa-regular fa-thumbs-up"></i>
            {{$video->likes()->count()}}
        </div>
        <div>
            <i class="fa-regular fa-thumbs-down"></i>
            {{$video->dislikes()->count()}}
        </div>
    </div>
    <div class="progress border border-primary">
        <div
            class="progress-bar bg-success"
            role="progressbar"
            aria-label="Likes ratio"
            style="width: {{$video->likes_ratio}}%;"
            aria-valuenow="{{$video->likes_ratio}}"
            aria-valuemin="0"
            aria-valuemax="100">
            {{$video->likes_ratio}}%
        </div>
        <div
            class="progress-bar bg-danger"
            role="progressbar"
            aria-label="Dislikes ratio"
            style="width: {{$video->dislikes_ratio}}%"
            aria-valuenow="{{$video->dislikes_ratio}}"
            aria-valuemin="0"
            aria-valuemax="100">
            {{$video->dislikes_ratio}}%
        </div>
    </div>
</a>
