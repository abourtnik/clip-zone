<article class="d-flex mb-2 position-relative gap-2">
    <a href="{{route('video.show', $video)}}">
        <div class="position-relative">
            <img class="" src="{{$video->poster_url}}" alt="{{$video->title}}" style="height: 94px">
            <small class="position-absolute bottom-0 right-0 p-1 text-white bg-dark">
                {{$video->duration}}
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div>
        <small class="d-block fw-bold">{{$video->title}}</small>
        <a href="{{route('pages.user', $video->user)}}" class="d-block text-muted text-sm position-relative">
            {{$video->user->username}}
        </a>
        <small class="text-muted">{{$video->views}} views • {{$video->created_at->diffForHumans()}}</small>
    </div>
</article>

