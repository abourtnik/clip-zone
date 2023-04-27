<article class="col">
    <div class="position-relative h-100 video-card">
        <a href="{{$video->route}}">
            <div class="position-relative">
                <image-loaded source="{{$video->thumbnail_url}}" title="{{$video->title}}" imgClass="img-fluid rounded w-100 video-thumbnail"/>
                <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                    {{$video->duration}}
                </small>
            </div>
            <span style="position: absolute;inset: 0;"></span>
        </a>
        <div class="d-flex mt-2 p-2 pt-sm-1 pb-sm-0 px-sm-0">
            <a href="{{$video->user->route}}" style=";height: 36px;" class="position-relative" title={{$video->user->username}}>
                <img class="rounded-circle" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar"  style="width: 36px">
            </a>
            <div class="ml-2">
                <a href="{{$video->route}}" class="fw-bolder text-decoration-none text-black d-block position-relative" title="{{$video->title}}">{{$video->short_title}}</a>
                <a href="{{$video->user->route}}" class="position-relative text-decoration-none">
                    <small>{{$video->user->username}}</small>
                </a>
                <small class="text-muted d-block">{{trans_choice('views', $video->views_count)}} • {{$video->created_at->diffForHumans()}}</small>
            </div>
        </div>
    </div>
</article>

