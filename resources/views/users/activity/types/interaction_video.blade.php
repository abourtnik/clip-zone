<div class="d-flex align-items-center position-relative">
    <div class="my-2 card card-body">
        <div class="d-flex gap-4 flex-column flex-sm-row align-items-stretch justify-content-between">
            <div @class(['rounded-circle text-white px-2 py-1', 'bg-success' => $activity->subject->status, 'bg-danger' => !$activity->subject->status]) style="height: 32px;width: 32px;">
                @if($activity->subject->status)
                    <i class="fa-solid fa-thumbs-up"></i>
                @else
                    <i class="fa-solid fa-thumbs-down"></i>
                @endif
            </div>
            <div class="d-flex flex-column gap-2 justify-content-between col-12 col-sm-8">
                <div class="text-muted text-sm">
                    @if($activity->subject->status)
                        {{ __('You liked the video') }}
                    @else
                        {{ __('You disliked the video') }}
                    @endif
                    <a href="{{$activity->subject->likeable->route}}" class="text-decoration-none text-sm">{{$activity->subject->likeable->title}}</a>
                </div>
            </div>
            <div class="d-flex flex-column gap-4 align-items-start align-items-sm-center">
                <small class="text-muted fw-bold text-center">{{$activity->perform_at->translatedFormat('d F Y - H:i')}}</small>
                <a href="{{$activity->subject->likeable->route}}">
                    @include('users.videos.partials.thumbnail', ['video' => $activity->subject->likeable])
                </a>
            </div>
        </div>
    </div>
</div>
