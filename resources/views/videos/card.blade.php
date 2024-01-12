<article class="col" x-data="{hover:false}">
    <div class="position-relative h-100">
        <a href="{{$video->route}}">
            <div class="position-relative">
                <image-loaded source="{{$video->thumbnail_url}}" title="{{$video->title}}" imgClass="img-fluid rounded-4 w-100" :hover="hover"/>
                <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                    {{$video->duration}}
                </small>
            </div>
            <span @mouseover="hover=true" @mouseleave="hover=false" style="position: absolute;inset: 0;"></span>
        </a>
        <div class="d-flex pt-2">
            <a href="{{$video->user->route}}" style=";height: 36px;" class="position-relative" title={{$video->user->username}}>
                <img class="rounded-circle" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar"  style="width: 36px">
            </a>
            <div class="ml-2">
                <a href="{{$video->route}}" class="fw-bolder text-decoration-none text-black d-block position-relative" title="{{$video->title}}">{{$video->short_title}}</a>
                <a href="{{$video->user->route}}" class="position-relative text-decoration-none">
                    <small>{{$video->user->username}}</small>
                </a>
                <small class="text-muted d-block">{{trans_choice('views', $video->views_count)}} • {{$video->publication_date->diffForHumans()}}</small>
            </div>
        </div>
    </div>
</article>

