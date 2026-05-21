<article class="d-flex flex-column flex-sm-row mb-3 position-relative card">
    <a href="{{$playlist->route}}" class="col-12 col-sm-6 col-lg-5 col-xl-4">
        <div class="position-relative h-100">
            @if($playlist->first_video)
                <img class="img-fluid w-100 h-100 tw:object-cover" src="{{$playlist->first_video->thumbnail_url}}" alt="{{$playlist->title}}">
            @else
                <div class="bg-secondary text-white d-flex justify-content-center align-items-center tw:w-[300px] tw:h-[200px]">
                    <i class="fa-solid fa-image fa-2x"></i>
                </div>
            @endif
            <small class="position-absolute bottom-0 right-0 p-2 fs-6 text-white bg-dark">
                Playlist
            </small>
        </div>
        <span class="position-absolute tw:inset-0"></span>
    </a>
    <div class="p-3 col-12 col-sm-6 col-lg-7 col-xl-8">
        <h6 class="mb-1">{{$playlist->title}}</h6>
        <small class="text-muted">{{trans_choice('videos', $playlist->videos_count)}} • {{$playlist->created_at->diffForHumans()}}</small>
        <a href="{{$playlist->user->route}}" class="d-flex align-items-center gap-2 text-muted text-sm position-relative text-decoration-none my-3">
            <img class="rounded-circle img-fluid tw:w-[35px]" src="{{$playlist->user->avatar_url}}" alt="{{$playlist->user->username}} avatar">
            <span>{{$playlist->user->username}}</span>
        </a>
    </div>
</article>
