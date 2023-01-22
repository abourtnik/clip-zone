<article class="col-md-6 col-lg-4 col-xl-3">
    <div class="position-relative">
        <a href="{{$video->route}}">
            <div class="position-relative">
                <img class="img-fluid w-100 rounded-4" src="{{$video->thumbnail_url}}" alt="{{$video->title}}" style="width: 360px; height: 202px;object-fit: cover;">
                <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                    {{$video->duration}}
                </small>
            </div>
            <span style="position: absolute;inset: 0;"></span>
        </a>
        <div class="d-flex mt-2">
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

