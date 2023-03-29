<article class="d-flex flex-column flex-sm-row mb-3 position-relative gap-3">
    <a href="{{$video->route}}">
        <div class="position-relative">
            <img class="" src="{{$video->thumbnail_url}}" alt="{{$video->title}}" style="height: 200px">
            <small class="position-absolute bottom-0 right-0 p-1 text-white bg-dark">
                {{$video->duration}}
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div>
        <h6 class="mb-1">{{$video->title}}</h6>
        <small class="text-muted">{{trans_choice('views', $video->views_count)}} • {{$video->created_at->diffForHumans()}}</small>
        <a href="{{$video->user->route}}" class="d-flex align-items-center gap-2 text-muted text-sm position-relative text-decoration-none my-3">
            <img class="rounded-circle img-fluid" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 24px">
            <span>{{$video->user->username}}</span>
        </a>
        <small class="text-muted">{{Str::limit($video->description, 150)}}</small>
    </div>
    <hr class="d-block d-sm-none">
</article>

