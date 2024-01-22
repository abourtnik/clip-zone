<div class="d-flex align-items-center">
    <div class="my-2 card card-body">
        <div class="d-flex gap-4 flex-column flex-sm-row align-items-stretch justify-content-between">
            <div class="rounded-circle bg-primary text-white px-2 py-1" style="height: 32px;width: 32px;">
                <i class="fa-solid fa-comment"></i>
            </div>
            <div class="d-flex flex-column gap-2 justify-content-between col-12 col-sm-8">
                <x-expand-item color="black">
                    {{$activity->subject->content}}
                </x-expand-item>
                <div class="text-muted text-sm">
                    @if($activity->subject->is_reply)
                        {{ __('You respond to a comment on the video') }}
                    @else
                        {{ __('You posted a comment on the video') }}
                    @endif
                    <a href="{{$activity->subject->video->route}}" class="text-decoration-none text-sm">{{$activity->subject->video->title}}</a>
                </div>
            </div>
            <div class="d-flex flex-column gap-4 align-items-start align-items-sm-center">
                <small class="text-muted fw-bold text-center">{{$activity->perform_at->translatedFormat('d F Y - H:i')}}</small>
                <a href="{{$activity->subject->video->route}}">
                    @include('users.videos.partials.thumbnail', ['video' => $activity->subject->video])
                </a>
            </div>
        </div>
    </div>
</div>
