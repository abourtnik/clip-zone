<article class="col-md-6 col-lg-4 col-xl-3">
    <div class="position-relative">
        <a href="{{$playlist->route}}">
            <div class="position-relative">
                <img class="img-fluid w-100 rounded-4" src="{{$playlist->videos->first()->thumbnail_url}}" alt="{{$playlist->title}}" style="width: 360px; height: 202px;object-fit: cover;">
            </div>
            <span style="position: absolute;inset: 0;"></span>
        </a>
        <div class="d-flex mt-2">
            <div class="ml-2">
                <div class="d-flex align-items-center gap-2">
                    <a href="{{$playlist->route}}" class="fw-bolder text-decoration-none text-black d-block position-relative" title="{{$playlist->title}}">
                        {{$playlist->title}}
                    </a>
                    <span>•</span>
                    <span>{{trans_choice('videos', $playlist->videos_count)}}</span>
                </div>
                <small class="text-muted d-block">{{$playlist->created_at->diffForHumans()}}</small>
            </div>
        </div>
    </div>
</article>
