<div class="d-flex align-items-center position-relative">
    <div class="my-2 card card-body">
        <div class="d-flex gap-4 align-items-start">
            <div @class(['rounded-circle text-white px-2 py-1', 'bg-success' => $activity->getExtraProperty('status'), 'bg-danger' => !$activity->getExtraProperty('status')])>
                @if($activity->getExtraProperty('status'))
                    <i class="fa-solid fa-thumbs-up"></i>
                @else
                    <i class="fa-solid fa-thumbs-down"></i>
                @endif
            </div>
            <div class="d-flex gap-3 justify-content-between w-100 flex-wrap">
                <div class="w-75">
                    <div class="d-flex flex-column flex-sm-row gap-4">
                        @if($activity->getExtraProperty('status'))
                            <strong>Liked Comment</strong>
                        @else
                            <strong>Disliked Comment</strong>
                        @endif
                        <a href="{{$activity->subject->likeable->video->route}}" class="text-decoration-none text-sm">{{$activity->subject->likeable->video->title}}</a>
                        <a href="{{$activity->subject->likeable->video->route}}">
                            @include('users.videos.partials.thumbnail', ['video' => $activity->subject->likeable->video])
                        </a>
                    </div>
                    <hr>
                    <x-expand-item>
                        {{$activity->subject->likeable->content}}
                    </x-expand-item>
                </div>
                <small class="text-muted fw-bold">{{$activity->updated_at->format('H:i')}}</small>
            </div>
        </div>
    </div>
</div>
