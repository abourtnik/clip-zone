@extends('layouts.user')

@section('title', 'Channel videos')

@section('content')
    @if($videos->total() || $filters)
        {{ Breadcrumbs::render('videos') }}
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Videos</h2>
            <div>
                <button class="btn btn-success d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#video_create">
                    <i class="fa-solid fa-upload"></i>
                    &nbsp;<span class="d-none d-sm-block">Import new video</span>
                </button>
            </div>
        </div>
        <hr>
        <form class="my-4 d-flex flex-column flex-xl-row gap-3 align-items-center align-items-xl-end" method="GET">
            <div class="row w-100">
                <div class="col-12 col-sm-4 col-xl">
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
                <div class="col-12 col-sm-6 col-xl">
                    <label for="publication_date_start" class="form-label fw-bold">Publication date start</label>
                    <input type="datetime-local" name="date[]" class="form-control" id="publication_date_start" value="{{$filters['date'][0] ?? null}}">
                </div>
                <div class="col-12 col-sm-6 col-xl">
                    <label for="publication_date_end" class="form-label fw-bold">Publication date end</label>
                    <input type="datetime-local" name="date[]" class="form-control" id="publication_date_end" value="{{$filters['date'][1] ?? null}}">
                </div>
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
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col" style="width: 40%">Video</th>
                    <th scope="col">Visibility</th>
                    <th scope="col" class="">
                        Date
                    </th>
                    <th scope="col">Views</th>
                    <th scope="col">Category</th>
                    <th scope="col">Comments</th>
                    <th scope="col">Interactions</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($videos as $video)
                    <tr class="bg-light">
                        <td class="d-block d-xxl-flex gap-3">
                            <a href="{{$video->route}}">
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
                        </td>
                        <td class="align-middle">
                            @include('videos.status')
                        </td>
                        <td class="align-middle">
                            @if($video->is_private || $video->is_unlisted || $video->is_draft)
                                <small>{{$video->created_at->format('d F Y H:i')}}</small>
                                <div class="text-sm text-muted">Uploaded</div>
                            @elseif($video->is_planned)
                                <small>{{$video->scheduled_date->format('d F Y H:i')}}</small>
                                <div class="text-sm text-muted">Scheduled</div>
                            @else
                                <small>{{$video->publication_date->format('d F Y H:i')}}</small>
                                <div class="text-sm text-muted">Published</div>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if(!$video->is_draft)
                                @if($video->views_count)
                                    <a href="{{route('user.videos.show', $video)}}" class="badge bg-info text-decoration-none">
                                        {{trans_choice('views', $video->views_count)}}
                                    </a>
                                @else
                                    <div class="badge bg-secondary">
                                        No views
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td class="align-middle">
                            @if(!$video->is_draft)
                                @if($video->category)
                                    <div class="badge bg-primary">
                                        {{$video->category->title}}
                                    </div>
                                @else
                                    <div class="badge bg-secondary">
                                        No category
                                    </div>
                                @endif
                            @endif
                        </td>
                        <td class="align-middle">
                            @if(!$video->is_draft)
                                @if($video->comments_count)
                                    <a href="{{route('user.comments.index') .'?video='.$video->id}}" class="badge bg-info text-decoration-none">
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
                                @if($video->is_draft)
                                    <a href="{{route('user.videos.create', $video)}}" class="btn btn-primary btn-sm" title="Edit draft">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                @else
                                    <a href="{{route('user.videos.edit', $video)}}" class="btn btn-primary btn-sm" title="Edit video">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                @endif
                                <button
                                    type="button"
                                    title="Delete video"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#delete_video"
                                    data-title="{{$video->title}}"
                                    data-infos="{{trans_choice('views', $video->views_count)}} • {{$video->created_at->format('d F Y')}}"
                                    data-poster="{{$video->thumbnail_url}}"
                                    data-route="{{route('user.videos.destroy', $video)}}"
                                    data-download="{{route('video.download', $video)}}"
                                    data-alt="{{$video->title}} Thumbnail"
                                    data-comments="{{trans_choice('comments', $video->comments_count)}}"
                                    data-likes="{{trans_choice('likes', $video->likes_count)}}"
                                    data-dislikes="{{trans_choice('dislikes', $video->dislikes_count)}}"
                                    data-elements='{{json_encode(['title' => '', 'infos' => '', 'poster' => 'src', 'route' => 'action', 'download' => 'href', 'alt' => 'alt', 'comments' => '', 'likes' => '', 'dislikes' => ''])}}'
                                >
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                @if(!$video->is_draft)
                                    <a href="{{route('user.videos.show', $video)}}" class="btn btn-success btn-sm" title="Video statistics">
                                        <i class="fa-solid fa-chart-simple"></i>
                                    </a>
                                @endif
                                @if($video->is_public && $video->is_pinned)
                                    <form action="{{route('user.videos.unpin', $video)}}" method="POST" class="d-inline-block">
                                        <button class="btn btn-secondary btn-sm" title="Unpin video" type="submit">
                                            <i class="fa-solid fa-link-slash"></i>
                                        </button>
                                    </form>
                                @elseif ($video->is_public && !$video->is_pinned)
                                    <form action="{{route('user.videos.pin', $video)}}" method="POST" class="d-inline-block">
                                        <button class="btn btn-secondary btn-sm" title="Pin video" type="submit">
                                            <i class="fa-solid fa-thumbtack"></i>
                                        </button>
                                    </form>
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
        @include('users.videos.modals.delete')
        @include('videos.modals.interactions')
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-upload fa-2x"></i>
                    <h5 class="my-3">Import your first video</h5>
                    <div class="text-muted my-3">Some description</div>
                    <button class="btn btn-success btn" data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-plus"></i>
                        Import
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
