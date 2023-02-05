<div class="d-flex align-items-center position-relative">
    <div class="my-2 card card-body">
        <div class="d-flex gap-4 align-items-start">
            <div class="rounded-circle bg-primary text-white px-2 py-1">
                <i class="fa-solid fa-comment"></i>
            </div>
            <div class="d-flex gap-3 justify-content-between w-100">
                <div class="w-75">
                    <div class="d-flex gap-4 align-items-start">
                        <strong>Commented</strong>
                        <a href="{{$activity->subject->video->route}}" class="text-decoration-none text-sm">{{$activity->subject->video->title}}</a>
                        <a href="{{$activity->subject->video->route}}">
                            <img class="" src="{{$activity->subject->video->thumbnail_url}}" alt="{{$activity->subject->video->title}}" style="width: 120px;height: 68px">
                        </a>
                    </div>
                    <hr class="my-3">
                    <div class="text-muted text-sm fst-italic my-3">{{$activity->subject->content}}</div>
                </div>
                <small class="text-muted fw-bold">{{$activity->created_at->format('H:i')}}</small>
            </div>
        </div>
    </div>
</div>
