<article class="col-3 mb-4 position-relative gap-4">
    <a href="{{route('pages.video', $video)}}">
        <div class="position-relative">
            <img class="img-fluid w-100" src="{{$video->poster_url}}" alt="{{$video->title}}" style="width: 360px; height: 200px">
            <small class="position-absolute bottom-0 right-0 p-1 text-white bg-dark">
                7:03
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div class="d-flex mt-2" style="width: 360px;">
        <a href="{{route('pages.user', $video->user)}}" style="width: 48px;height: 48px;" class="position-relative">
            <img class="rounded-circle img-fluid" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" >
        </a>
        <div class="ml-2">
            <div style="font-size: 1.0rem;">{{$video->title}}</div>
            <a href="{{route('pages.user', $video->user)}}" class="position-relative">{{$video->user->username}}</a>
            <small class="text-muted d-block">{{$video->views}} views - {{$video->created_at->diffForHumans()}}</small>
        </div>
    </div>
</article>

