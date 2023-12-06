<article class="d-flex flex-column position-relative col-12 col-sm-6 col-md-6 col-lg-4 col-xxl-3">
    <a href="{{$video->route}}">
        <div class="position-relative">
            <image-loaded source="{{$video->thumbnail_url}}" title="{{$video->title}}" imgClass="rounded-2 img-fluid"/>
            <small class="position-absolute bottom-0 right-0 p-1 m-1 text-white bg-dark fw-bold rounded" style="font-size: 0.70rem;">
                {{$video->duration}}
            </small>
        </div>
        <span style="position: absolute;inset: 0;"></span>
    </a>
    <div class="pt-2">
        <a href="{{$video->route}}" class="d-block fw-bold position-relative text-decoration-none text-black text-sm mb-1 overflow-hidden" title="{{$video->title}}" style="max-height: 40px">
            {{Str::limit($video->title, 50)}}
        </a>
        <div class="d-flex flex-wrap gap-1 align-items-center text-muted text-sm">
            <a href="{{$video->user->route}}" class="d-block text-muted position-relative text-decoration-none" title="{{$video->user->username}}">
                {{$video->user->username}}
            </a>
            <span>•</span>
            <span>
                {{$video->publication_date->diffForHumans()}}
            </span>
        </div>
        <small class="text-muted text-sm">{{trans_choice('views', $video->views_count)}}</small>
    </div>
</article>
