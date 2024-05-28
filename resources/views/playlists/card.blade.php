<article class="col-md-6 col-lg-4 col-xl-3" x-data="{hover:false}">
    <div class="position-relative" @mouseover="hover=true" @mouseleave="hover=false">
        <a href="{{$playlist->first_video->routeWithParams(['list' => $playlist->uuid])}}">
            <div class="position-relative">
                <div class="card-stack position-absolute h-100 rounded-4"></div>
                <div class="z-2" style="position: inherit;">
                    @if($playlist->first_video)
                        <img class="img-fluid w-100 rounded-4" src="{{$playlist->first_video->thumbnail_url}}" alt="{{$playlist->title}} Thumbnail" style="width: 360px; height: 202px;object-fit: cover;">
                    @else
                        <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="height: 202px;">
                            <i class="fa-solid fa-image fa-2x"></i>
                        </div>
                    @endif
                    <small class="position-absolute bottom-0 right-0 p-1 m-2 text-white bg-dark fw-bold rounded d-flex gap-2 align-items-center">
                        <i class="fa-solid fa-list"></i>
                        <span>{{trans_choice('videos', $playlist->videos_count)}}</span>
                    </small>
                </div>
                <div x-show.important="hover" style="inset:0;" class="bg-dark position-absolute d-flex justify-content-center align-items-center bg-opacity-75 rounded-4 z-2">
                    <div class="d-flex gap-2 text-white align-items-center">
                        <i class="fa-solid fa-play"></i>
                        <span class="text-uppercase">{{ __('Play All') }}</span>
                    </div>
                </div>
            </div>
            <span style="position: absolute;inset: 0;" ></span>
        </a>
        <div class="d-flex mt-2">
            <div class="ml-2">
                <a href="{{$playlist->route}}" class="fw-bolder text-decoration-none text-black d-block position-relative text-break" title="{{$playlist->title}}">
                    {{Str::limit($playlist->title, 69)}}
                </a>
                <div class="text-sm text-muted">{{$playlist->created_at->diffForHumans()}}</div>
                <a href="{{$playlist->route}}" class="fw-bolder text-sm text-decoration-none text-muted d-block position-relative mt-2" title="{{$playlist->title}}">
                    {{ __('View full playlist') }}
                </a>
            </div>
        </div>
    </div>
</article>
