<article class="col-3 mb-4 gap-4">
    <div class="position-relative">
        <a href="{{route('video.show', $video)}}" >
            <div class="position-relative">
                <img class="img-fluid w-100" src="{{$video->poster_url}}" alt="{{$video->title}}" style="width: 360px; height: 200px">
                <small class="position-absolute bottom-0 right-0 p-1 text-white bg-dark">
                    {{$video->duration}}
                </small>
            </div>
            <span style="position: absolute;inset: 0;"></span>
        </a>
        <div class="d-flex mt-2" style="width: 360px;">
            <a href="{{route('pages.user', $video->user)}}" style="width: 48px;height: 48px;" class="position-relative">
                <img class="rounded-circle img-fluid" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" >
            </a>
            <div class="ml-2">
                <div class="fw-bolder">{{$video->title}}</div>
                <a href="{{route('pages.user', $video->user)}}" class="position-relative">
                    <small>{{$video->user->username}}</small>
                </a>
                <small class="text-muted d-block">{{$video->views}} views • {{$video->created_at->diffForHumans()}}</small>
            </div>
        </div>
    </div>
</article>

