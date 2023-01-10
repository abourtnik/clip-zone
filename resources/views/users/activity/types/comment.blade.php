<div class="d-flex align-items-center position-relative">
    <div class="position-absolute d-flex justify-content-center align-items-center rounded-circle bg-primary text-white p-2" style="width: 30px;height: 30px;left: -6.3%">
        <i class="fa-solid fa-comment"></i>
    </div>
    <div class="my-4 card card-body">
        <div class="d-flex gap-3">
            <div class="w-25">
                <div class="d-flex gap-3">
                    <strong>Commented</strong>
                    <a href="{{$activity->subject->video->route}}" class="text-decoration-none text-sm">{{$activity->subject->video->title}}</a>
                </div>
                <small class="d-block my-2">
                    <a href="{{$activity->subject->video->user->route}}" class="text-decoration-none">{{$activity->subject->video->user->username}}</a>
                </small>
                <div class="text-muted text-sm fst-italic my-3">{{$activity->subject->content}}</div>
                <small class="text-muted">{{$activity->created_at->format('H:i')}}</small>
            </div>
            <a href="{{$activity->subject->video->route}}">
                <img class="img-fluid" src="{{$activity->subject->video->thumbnail_url}}" alt="{{$activity->subject->video->title}}" style="width: 120px;height: 68px">
            </a>
        </div>
    </div>
</div>
