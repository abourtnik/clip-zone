<article @class(["d-flex flex-wrap flex-sm-nowrap position-relative gap-0 gap-sm-2 suggested_video", "mb-4 mb-sm-2" => !isset($playlist_video)]) x-data="{hover:false}">
    <a href="{{$video->route}}">
        <div class="position-relative">
            <image-loaded source="{{$video->thumbnail_url}}" title="{{$video->title}}" imgClass="rounded-1-sm" :hover="hover"/>
            <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                {{$video->duration}}
            </small>
        </div>
        <span @mouseover="hover=true" @mouseleave="hover=false" style="position: absolute;inset: 0;"></span>
    </a>
    <div class="pt-2 px-2 px-sm-0">
        <div class="d-flex gap-2">
            <a href="{{$video->user->route}}" style=";height: 36px;" class="position-relative d-block d-sm-none" title={{$video->user->username}}>
                <img class="rounded-circle" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar"  style="width: 36px">
            </a>
            <div>
                <a href="{{$video->route}}" class="d-block fw-bold position-relative text-decoration-none text-black text-sm mb-1 overflow-hidden text-break" title="{{$video->title}}" style="max-height: 40px">
                    {{Str::limit($video->title, 50)}}
                </a>
                <a href="{{$video->user->route}}" class="d-block text-muted position-relative text-decoration-none text-sm" title="{{$video->user->username}}">
                    {{$video->user->username}}
                </a>
                <div class="text-sm d-flex flex-wrap gap-1 align-items-center text-muted">
                    <div>{{trans_choice('views', $video->views_count)}}</div>
                    <div>•</div>
                    <div>{{$video->publication_date->diffForHumans()}}</div>
                </div>
            </div>
        </div>
    </div>
</article>
