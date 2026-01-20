<div class="mb-2 card card-body py-2 px-3">
    <div class="d-flex gap-2 flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
        <div class="d-flex flex-column gap-2 text-sm">
            @if($activity->subject->likeable->trashed())
                <span class="text-danger fw-bold">{{ __('This comment was deleted') }}</span>
            @elseif($activity->subject->likeable->is_banned)
                <div class="text-danger fw-bold">{{ __('This comment was banned') }}</div>
            @else
                <x-expand-item>
                    {{$activity->subject->likeable->content}}
                </x-expand-item>
            @endif
            <div class="text-muted text-sm">
                @if($activity->subject->status)
                    {{ __('You liked a comment on the video') }}
                @else
                    {{ __('You disliked a comment on the video') }}
                @endif
                <a href="{{$activity->subject->likeable->video->route}}" class="text-decoration-none text-sm">{{$activity->subject->likeable->video->title}}</a>
            </div>
            <small class="text-muted">{{$activity->perform_at->translatedFormat('d F Y - H:i')}}</small>
        </div>
        <a href="{{$activity->subject->likeable->video->route}}" class="text-decoration-none">
            @include('users.videos.partials.thumbnail', ['video' => $activity->subject->likeable->video])
        </a>
    </div>
</div>
