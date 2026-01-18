<div class="d-flex align-items-center">
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
                @if($activity->subject->likeable->trashed())
                    <div class="alert alert-info">{{ __('This comment was deleted') }}</div>
                @elseif($activity->subject->likeable->is_banned)
                    <div class="alert alert-danger">{{ __('This comment was banned') }}</div>
                @else
                    <div class="p-3 bg-dark-subtle text-dark-emphasis">
                        <x-expand-item >
                            {{$activity->subject->likeable->content}}
                        </x-expand-item>
                    </div>
                @endif
                <div class="text-muted text-sm">
                    @if($activity->subject->status)
                        {{ __('You liked a comment on the video') }}
                    @else
                        {{ __('You disliked a comment on the video') }}
                    @endif
                    <a href="{{$activity->subject->likeable->video->route}}" class="text-decoration-none text-sm">{{$activity->subject->likeable->video->title}}</a>
                </div>
            </div>
            <div class="d-flex flex-column gap-4 align-items-start align-items-sm-center">
                <small class="text-muted fw-bold text-center">{{$activity->perform_at->translatedFormat('d F Y - H:i')}}</small>
                <a href="{{$activity->subject->likeable->video->route}}">
                    @include('users.videos.partials.thumbnail', ['video' => $activity->subject->likeable->video])
                </a>
            </div>
        </div>
    </div>
</div>
