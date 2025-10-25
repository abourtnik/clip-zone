<article class="d-flex flex-column flex-sm-row mb-3 position-relative card">
    <a href="{{$video->route}}" class="col-12 col-sm-6 col-lg-5 col-xl-4">
        <div class="position-relative h-100">
            <image-loaded source="{{$video->thumbnail_url}}" title="{{$video->title}}" class="img-fluid w-100 h-100" style="height: 200px"/>
            <small class="position-absolute bottom-0 right-0 p-1 text-white bg-dark">
                {{$video->duration}}
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div class="p-3 col-12 col-sm-6 col-lg-7 col-xl-8">
        <h6 class="mb-1">{{$video->title}}</h6>
        <small class="text-muted">{{trans_choice('views', $video->views)}} • {{$video->published_at->diffForHumans()}}</small>
        <a href="{{$video->user->route}}" class="d-flex align-items-center gap-2 text-muted text-sm position-relative text-decoration-none my-3">
            <img class="rounded-circle img-fluid" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 35px">
            <span>{{$video->user->username}}</span>
        </a>
        <small class="text-muted">{{Str::limit($video->description, 150)}}</small>
    </div>
</article>

