<div class="d-flex align-items-center position-relative">
    <div class="position-absolute d-flex justify-content-center align-items-center rounded-circle bg-primary text-white p-2" style="width: 30px;height: 30px;left: -8.9%">
        <i class="fa-solid fa-comment"></i>
    </div>
    <div class="my-4 card card-body">
        <div class="d-flex gap-3">
            <div class="d-flex gap-3 justify-content-between w-100">
                <div class="w-50">
                    <div class="d-flex gap-4">
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
