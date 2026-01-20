<div class="card card-body py-2 px-3 mb-2">
    <div class="d-flex gap-2 flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center">
        <div class="d-flex flex-column gap-2 text-sm">
            <div class="text-muted text-sm">
                @if($activity->subject->status)
                    {{ __('You liked video') }}
                @else
                    {{ __('You disliked video') }}
                @endif
                <a href="{{$activity->subject->likeable->route}}" class="text-decoration-none text-sm">
                    <span class="text-primary">{{$activity->subject->likeable->title}}</span>
                </a>
            </div>
            <small class="text-muted">{{$activity->perform_at->translatedFormat('d F Y - H:i')}}</small>
        </div>
        <a href="{{$activity->subject->likeable->route}}" class="text-decoration-none">
            @include('users.videos.partials.thumbnail', ['video' => $activity->subject->likeable])
        </a>
    </div>
</div>
