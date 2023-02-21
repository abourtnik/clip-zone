<div class="d-flex gap-3 align-items-start">
    <a href="{{$report->reportable->route}}">
        <img src="{{$report->reportable->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
    </a>
    <div class="d-flex flex-column gap-1">
        <small>{{Str::limit($report->reportable->title, 100), '...'}}</small>
        <a class="text-primary text-decoration-none text-sm" href="{{$report->reportable->user->route}}">
            {{$report->reportable->user->username}}
        </a>
    </div>
</div>
