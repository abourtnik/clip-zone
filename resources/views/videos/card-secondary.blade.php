<article class="d-flex mb-2 position-relative gap-2">
    <a href="{{$video->route}}">
        <div class="position-relative">
            <img class="rounded-4" src="{{$video->thumbnail_url}}" alt="{{$video->title}}" style="height: 94px">
            <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                {{$video->duration}}
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div>
        <a href="{{$video->route}}" class="d-block fw-bold position-relative text-decoration-none text-black text-sm mb-1" title="{{$video->title}}">{{Str::limit($video->title, 55)}}</a>
        <a href="{{$video->user->route}}" class="d-block text-muted text-sm position-relative text-decoration-none" title="{{$video->user->username}}">
            {{$video->user->username}}
        </a>
        <small class="text-muted">{{trans_choice('views', $video->views_count)}} • {{$video->created_at->diffForHumans()}}</small>
    </div>
</article>

