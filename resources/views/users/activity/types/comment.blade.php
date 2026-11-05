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
            <div class="d-flex gap-2 align-items-center tw:text-xs">
                <span class="text-muted">{{$activity->perform_at->translatedFormat('d F Y - H:i')}}</span>
                @if($activity->subject->is_banned)
                    <div class="d-flex align-items-center gap-1">
                        <span class="text-muted">•</span>
                        <span class="text-danger fw-semibold">
                            {{ __('Banned :date', ['date' => $activity->subject->banned_at->diffForHumans()]) }}
                        </span>
                    </div>
                @elseif($activity->subject->is_banned->trashed())
                    <div class="d-flex align-items-center gap-1">
                        <span class="text-muted">•</span>
                        <span class="text-danger fw-semibold">
                            {{ __('Deleted :date', ['date' => $activity->subject->deleted_at->diffForHumans()]) }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
        <a href="{{$activity->subject->video->route}}" class="text-decoration-none">
            @include('users.videos.partials.thumbnail', ['video' => $activity->subject->video])
        </a>
    </div>
</div>
