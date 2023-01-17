<div class="d-flex align-items-center position-relative">
    <div class="position-absolute d-flex justify-content-center align-items-center rounded-circle bg-primary text-white p-2" style="width: 30px;height: 30px;left: -6.3%">
        @if($activity->getExtraProperty('status'))
            <i class="fa-solid fa-thumbs-up"></i>
        @else
            <i class="fa-solid fa-thumbs-down"></i>
        @endif
    </div>
    <div class="my-4 card card-body">
        <div class="d-flex gap-3">
            <div class="w-25">
                <div class="d-flex gap-3">
                    @if($activity->getExtraProperty('status'))
                        <strong>Liked</strong>
                    @else
                        <strong>Disliked</strong>
                    @endif
                    <a href="{{$activity->subject->likeable->route}}" class="text-decoration-none text-sm">{{$activity->subject->likeable->title}}</a>
                </div>
                <small class="d-block my-2">
                    <a href="{{$activity->subject->likeable->user->route}}" class="text-decoration-none">{{$activity->subject->likeable->user->username}}</a>
                </small>
                <small class="text-muted">{{$activity->created_at->format('H:i')}}</small>
            </div>
            <a href="{{$activity->subject->likeable->route}}">
                <img class="img-fluid" src="{{$activity->subject->likeable->thumbnail_url}}" alt="{{$activity->subject->likeable->title}}" style="width: 120px;height: 68px">
            </a>
        </div>
    </div>
</div>
