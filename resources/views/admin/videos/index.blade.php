@extends('layouts.admin')

@section('title', 'Videos')

@section('content')

<div class="d-flex justify-content-between align-items-center my-3">
    <h2>Videos</h2>
</div>
<hr>
<form class="my-4 d-flex gap-3 align-items-end" method="GET">
    <div class="col">
        <label for="search" class="form-label fw-bold">Search</label>
        <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
    </div>
    <div class="col-12 col-sm-4 col-xl">
        <label for="status" class="form-label fw-bold">Status</label>
        <select name="status" class="form-select" aria-label="Default select example">
            <option selected value="">All</option>
            @foreach($status as $id => $name)
                <option @selected(($filters['status'] ?? null) === (string) $id) value="{{$id}}">{{$name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-sm-4 col-xl">
        <label for="category" class="form-label fw-bold">Category</label>
        <select name="category" class="form-select" aria-label="Default select example">
            <option selected value="">All</option>
            <option @selected(($filters['category'] ?? null) === 'without') value="without">Without category</option>
            @foreach($categories as $category)
                <option @selected(($filters['category'] ?? null) === (string) $category->id) value="{{$category->id}}">{{$category->title}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12 col-sm-4 col-xl">
        <label for="category" class="form-label fw-bold">User</label>
        <select name="user" class="form-select" aria-label="Default select example">
            <option selected value="">All</option>
            @foreach($users as $user)
                <option @selected(($filters['user'] ?? null) === (string) $user->id) value="{{$user->id}}">{{$user->username}}</option>
            @endforeach
        </select>
    </div>
    <div class="col">
        <label for="date_start" class="form-label fw-bold">Publication date start</label>
        <input type="datetime-local" name="date[]" class="form-control" id="date_start" value="{{$filters['date'][0] ?? null}}">
    </div>
    <div class="col">
        <label for="date_end" class="form-label fw-bold">Publication date end</label>
        <input type="datetime-local" name="date[]" class="form-control" id="date_end" value="{{$filters['date'][1] ?? null}}">
    </div>
    <div class="btn-group">
        <button type="submit" class="btn btn-outline-secondary" title="Search">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
        <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
            <i class="fa-solid fa-eraser"></i>
        </a>
    </div>
</form>
<table class="table table-bordered table-striped">
    <thead>
    <tr style="border-top: 3px solid #0D6EFD;">
        <th style="width: 15%" scope="col">User</th>
        <th scope="col" class="w-35">Video</th>
        <th scope="col">Visibility</th>
        <th style="width: 15%" scope="col">Date</th>
        <th scope="col">Views</th>
        <th scope="col">Comments</th>
        <th scope="col">Interactions</th>
        <th scope="col" style="width: 7%">Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse($videos as $video)
        <tr class="bg-light">
            <td class="align-middle">
                <a href="{{$video->user->route}}" class="d-flex align-items-start gap-3 text-decoration-none">
                    <img class="rounded" src="{{$video->user->avatar_url}}" alt="{{$video->user->username}} avatar" style="width: 50px;">
                    <div class="d-flex flex-column">
                        <span>{{$video->user->username}}</span>
                        <span class="text-muted text-sm"> {{$video->user->subscribers_count}} subscribers • {{$video->user->videos_count}} videos</span>
                    </div>
                </a>
            </td>
            <td class="align-middle">
                @if(!$video->is_draft)
                    @if($video->category)
                        <div class="badge bg-primary my-3">
                            {{$video->category->title}}
                        </div>
                    @else
                        <div class="badge bg-secondary my-3">
                            No category
                        </div>
                    @endif
                @endif
                <div class="d-flex gap-2">
                    <a class="d-block " href="{{$video->route}}">
                        @if($video->is_draft)
                            <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 120px;height: 68px">
                                <i class="fa-solid fa-image fa-2x"></i>
                            </div>
                        @else
                            <img src="{{$video->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
                        @endif
                    </a>
                    <div>
                        <div>{{Str::limit($video->title, 100), '...'}}</div>
                        <small class="text-muted">{{Str::limit($video->description, 190), '...'}}</small>
                    </div>
                </div>
            </td>
            <td class="align-middle">
                @include('videos.status')
            </td>
            <td class="align-middle">
                @if($video->is_private || $video->is_unlisted || $video->is_draft)
                    {{$video->created_at->format('d F Y H:i')}}
                    <div class="text-sm text-muted">Uploaded</div>
                @elseif($video->is_planned)
                    {{$video->scheduled_date->format('d F Y H:i')}}
                    <div class="text-sm text-muted">Scheduled</div>
                @else
                    {{$video->publication_date->format('d F Y H:i')}}
                    <div class="text-sm text-muted">Published</div>
                @endif
            </td>
            <td class="align-middle">
                @if(!$video->is_draft)
                    @if($video->views_count)
                        <span class="badge bg-secondary">{{$video->views_count}} views</span>
                    @else
                        <span class="badge bg-secondary">No views</span>
                    @endif
                @endif
            </td>
            <td class="align-middle">
                @if(!$video->is_draft)
                    @if($video->comments_count)
                        <a href="{{route('admin.comments.index') .'?video='.$video->id}}" class="badge bg-info text-decoration-none">
                            {{trans_choice('comments', $video->comments_count)}}
                        </a>
                    @else
                        <div class="badge bg-secondary">
                            No comments
                        </div>
                    @endif
                    @if(!$video->allow_comments)
                        <div class="badge bg-danger">
                            Disabled
                        </div>
                    @endif
                @endif
            </td>
            <td class="align-middle">
                @if(!$video->is_draft)
                    @include('users.partials.interactions', ['item' => $video])
                    <div class="d-flex align-items-center gap-1">
                        @if($video->interactions_count)
                            <div class="badge bg-primary mt-2" data-bs-toggle="modal" data-bs-target="#video_likes" style="cursor: pointer;" data-id="{{$video->id}}">
                                Details
                            </div>
                        @endif
                        @if(!$video->show_likes)
                            <div class="badge bg-danger mt-2">
                                Disabled
                            </div>
                        @endif
                    </div>
                @endif
            </td>
            <td class="align-middle">
                <a href="{{route('user.videos.show', $video)}}" class="btn btn-success btn-sm" title="Video statistics">
                    <i class="fa-solid fa-chart-simple"></i>
                </a>
                @if(!$video->is_banned)
                    <button
                        type="button"
                        title="Ban video"
                        class="btn btn-danger btn-sm"
                        data-bs-toggle="modal"
                        data-bs-target="#ban_video"
                        data-title="{{$video->title}}"
                        data-infos="{{trans_choice('views', $video->views_count)}} • {{$video->created_at->format('d F Y')}}"
                        data-poster="{{$video->thumbnail_url}}"
                        data-route="{{route('admin.videos.ban', $video)}}"
                    >
                        <i class="fa-solid fa-ban"></i>
                    </button>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center">
                <i class="fa-solid fa-database fa-2x my-3"></i>
                <p class="fw-bold">No matching videos</p>
            </td>
        </tr>
    @endforelse
    </tbody>
</table>
{{ $videos->links() }}
@include('videos.modals.interactions')
@include('admin.videos.modals.ban')
@endsection
