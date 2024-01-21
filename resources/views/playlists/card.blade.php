<article class="col-md-6 col-lg-4 col-xl-3">
    <div class="position-relative">
        <a href="{{$playlist->route}}">
            <div class="position-relative">
                @if($playlist->thumbnail)
                    <img class="img-fluid w-100 rounded-4" src="{{$playlist->thumbnail}}" alt="{{$playlist->title}} Thumbnail" style="width: 360px; height: 202px;object-fit: cover;">
                @else
                    <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="height: 202px;">
                        <i class="fa-solid fa-image fa-2x"></i>
                    </div>
                @endif
                <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded">
                    {{trans_choice('videos', $playlist->videos_count)}}
                </small>
            </div>
            <span style="position: absolute;inset: 0;"></span>
        </a>
        <div class="d-flex mt-2">
            <div class="ml-2">
                <a href="{{$playlist->route}}" class="fw-bolder text-decoration-none text-black d-block position-relative text-break" title="{{$playlist->title}}">
                    {{Str::limit($playlist->title, 69)}}
                </a>
                <div class="text-sm text-muted">{{$playlist->created_at->diffForHumans()}}</div>
            </div>
        </div>
    </div>
</article>
