<div class="d-flex align-items-center position-relative">
    <div class="my-2 card card-body">
        <div class="d-flex gap-4 align-items-start">
            <div class="rounded-circle bg-primary text-white px-2 py-1">
                <i class="fa-solid fa-comment"></i>
            </div>
            <div class="d-flex gap-3 justify-content-between w-100">
                <div class="w-75">
                    <div class="d-flex flex-column flex-sm-row gap-4 align-items-start">
                        <strong>Commented</strong>
                        <a href="{{$activity->subject->video->route}}" class="text-decoration-none text-sm">{{$activity->subject->video->title}}</a>
                        <a href="{{$activity->subject->video->route}}">
                            @include('users.videos.partials.thumbnail', ['video' => $activity->subject->video])
                        </a>
                    </div>
                    <hr class="my-3">
                    <x-expand-item>
                        {{$activity->subject->content}}
                    </x-expand-item>
                </div>
                <small class="text-muted fw-bold">{{$activity->created_at->format('H:i')}}</small>
            </div>
        </div>
    </div>
</div>
