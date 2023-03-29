<article class="d-flex flex-column flex-sm-row mb-3 position-relative gap-3">
    <a href="{{$playlist->route}}">
        <div class="position-relative">
            <img src="{{$playlist->thumbnail}}" alt="{{$playlist->title}}" style="height: 200px">
            <small class="position-absolute bottom-0 right-0 p-2 fs-6 text-white bg-dark">
                Playlist
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div>
        <h6 class="mb-1">{{$playlist->title}}</h6>
        <small class="text-muted">{{trans_choice('videos', $playlist->videos_count)}} • {{$playlist->created_at->diffForHumans()}}</small>
        <a href="{{$playlist->user->route}}" class="d-flex align-items-center gap-2 text-muted text-sm position-relative text-decoration-none my-3">
            <img class="rounded-circle img-fluid" src="{{$playlist->user->avatar_url}}" alt="{{$playlist->user->username}} avatar" style="width: 24px">
            <span>{{$playlist->user->username}}</span>
        </a>
        <small class="text-muted">{{Str::limit($playlist->description, 150)}}</small>
    </div>
    <hr class="d-block d-sm-none">
</article>
