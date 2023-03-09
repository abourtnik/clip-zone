<div class="d-flex gap-3 align-items-start mb-3">
    <a href="{{$report->reportable->video->route}}">
        <img src="{{$report->reportable->video->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
    </a>
    <div class="d-flex flex-column gap-1">
        <small>{{Str::limit($report->reportable->video->title, 100), '...'}}</small>
        <a class="text-sm text-decoration-none" href="{{$report->reportable->video->user->route}}">{{$report->reportable->video->user->username}}</a>
    </div>
</div>
<a class="text-sm text-decoration-none badge rounded-pill text-bg-secondary" href="{{$report->reportable->user->username}}">{{$report->reportable->user->username}} : </a>
<x-expand-item>
    {{$report->reportable->content}}
</x-expand-item>
