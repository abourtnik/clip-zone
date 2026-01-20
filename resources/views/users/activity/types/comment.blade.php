<div class="mb-2 card card-body py-2 px-3">
    <div class="d-flex gap-2 flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
        <div class="d-flex flex-column gap-2 text-sm">
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
            <small class="text-muted">{{$activity->perform_at->translatedFormat('d F Y - H:i')}}</small>
        </div>
        <a href="{{$activity->subject->video->route}}" class="text-decoration-none">
            @include('users.videos.partials.thumbnail', ['video' => $activity->subject->video])
        </a>
    </div>
</div>
