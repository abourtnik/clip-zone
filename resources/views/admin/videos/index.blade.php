@extends('layouts.admin')

@section('title', 'Videos')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Videos</h2>
    </div>
    <hr>
    <div x-data="{ filters: window.innerWidth > 992 }">
        <button class="btn btn-primary btn-sm d-flex d-lg-none align-items-center gap-2 mb-3" @click="filters = !filters">
            <i class="fa-solid fa-filter"></i>
            <span>{{ __('Filters') }}</span>
            <i class="fa-solid fa-chevron-down" x-show.important="!filters" ></i>
            <i class="fa-solid fa-chevron-up" x-show.important="filters" ></i>
        </button>
        <form class="mb-4 row align-items-end gx-2 gy-2" method="GET" x-show.important="filters">
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl col-xxl-3">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="status" class="form-label fw-bold">Status</label>
                <select name="status" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($status as $id => $name)
                        <option @selected(($filters['status'] ?? null) === (string) $id) value="{{$id}}">{{$name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="category" class="form-label fw-bold">Category</label>
                <select name="category" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    <option @selected(($filters['category'] ?? null) === 'without') value="without">Without category</option>
                    @foreach($categories as $category)
                        <option @selected(($filters['category'] ?? null) === (string) $category->id) value="{{$category->id}}">{{$category->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <search-model name="user" endpoint="{{route('admin.search.users')}}" @isset($selectedUser)) value="{{$selectedUser}}" @endisset></search-model>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="date_start" class="form-label fw-bold">Publication date start</label>
                <input type="datetime-local" name="date_start" class="form-control" id="date_start" value="{{$filters['date_start'] ?? null}}">
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl">
                <label for="date_end" class="form-label fw-bold">Publication date end</label>
                <input type="datetime-local" name="date_end" class="form-control" id="date_end" value="{{$filters['date_end'] ?? null}}">
            </div>
            <div class="btn-group col-auto">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th style="min-width: 300px" scope="col">User</th>
                <th scope="col" style="min-width: 400px">Video</th>
                <th scope="col">Visibility</th>
                <th scope="col" style="min-width: 210px">Date</th>
                <th scope="col">Views</th>
                <th scope="col">Comments</th>
                <th scope="col" style="min-width: 140px">Interactions</th>
                <th scope="col">Actions</th>
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
                        <div class="d-flex gap-3">
                            <a class="d-block " href="{{$video->route}}">
                                @if($video->is_draft)
                                    <div class="bg-secondary text-white d-flex justify-content-center align-items-center" style="width: 120px;height: 68px">
                                        <i class="fa-solid fa-image fa-2x"></i>
                                    </div>
                                @else
                                    <img src="{{$video->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
                                @endif
                            </a>
                            <div class="text-sm">{{Str::limit($video->title, 100), '...'}}</div>
                        </div>
                    </td>
                    <td class="align-middle">
                        @include('videos.status')
                    </td>
                    <td class="align-middle">
                        @if($video->is_private || $video->is_unlisted || $video->is_draft || $video->is_failed)
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
                        <div class="d-flex gap-1">
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
                        </div>
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
    </div>
    {{ $videos->links() }}
    @include('videos.modals.interactions')
    @include('admin.videos.modals.ban')
@endsection
