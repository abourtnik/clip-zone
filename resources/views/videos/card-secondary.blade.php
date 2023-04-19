<article class="d-flex flex-wrap flex-sm-nowrap mb-2 position-relative gap-2 suggested_video video-card">
    <a href="{{$video->route}}">
        <div class="position-relative">
            <img class="rounded-2 video-thumbnail" src="{{$video->thumbnail_url}}" alt="{{$video->title}}">
            <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                {{$video->duration}}
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div class="p-2 pt-sm-1 pb-sm-0 px-sm-0">
        <div class="d-flex gap-2">
            <a href="{{$video->user->route}}" style=";height: 36px;" class="position-relative d-block d-sm-none" title={{$video->user->username}}>
                <img class="rounded-circle" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar"  style="width: 36px">
            </a>
            <div>
                <a href="{{$video->route}}" class="d-block fw-bold position-relative text-decoration-none text-black text-sm mb-1 overflow-hidden" title="{{$video->title}}" style="max-height: 40px">
                    {{Str::limit($video->title, 50)}}
                </a>
                <div class="d-flex flex-wrap gap-1 align-items-center text-muted text-sm">
                    <a href="{{$video->user->route}}" class="d-block text-muted position-relative text-decoration-none" title="{{$video->user->username}}">
                        {{$video->user->username}}
                    </a>
                    <span>•</span>
                    <span>{{$video->created_at->diffForHumans()}}</span>
                </div>
                <small class="text-muted text-sm">{{trans_choice('views', $video->views_count)}}</small>
            </div>
        </div>
    </div>
</article>
