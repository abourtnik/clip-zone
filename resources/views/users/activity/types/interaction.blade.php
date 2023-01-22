<div class="d-flex align-items-center position-relative">
    <div class="position-absolute d-flex justify-content-center align-items-center rounded-circle {{ $activity->getExtraProperty('status') ? 'bg-success' : 'bg-danger' }} text-white p-2" style="width: 30px;height: 30px;left: -8.9%">
        @if($activity->getExtraProperty('status'))
            <i class="fa-solid fa-thumbs-up"></i>
        @else
            <i class="fa-solid fa-thumbs-down"></i>
        @endif
    </div>
    <div class="my-4 card card-body">
        <div class="d-flex gap-3">
            <div class="d-flex gap-3 justify-content-between w-100">
                <div class="w-50">
                    <div class="d-flex gap-4">
                        @if($activity->getExtraProperty('status'))
                            <strong>Liked</strong>
                        @else
                            <strong>Disliked</strong>
                        @endif
                        <a href="{{$activity->subject->likeable->route}}" class="text-decoration-none text-sm">{{$activity->subject->likeable->title}}</a>
                        <a href="{{$activity->subject->likeable->route}}">
                            <img src="{{$activity->subject->likeable->thumbnail_url}}" alt="{{$activity->subject->likeable->title}}" style="width: 120px;height: 68px">
                        </a>
                    </div>
                </div>
                <small class="text-muted fw-bold">{{$activity->created_at->format('H:i')}}</small>
            </div>
        </div>
    </div>
</div>
