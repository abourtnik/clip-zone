@extends('layouts.user')

@section('content')
    @if($videos->total() || $filters)
        {{ Breadcrumbs::render('videos') }}
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Videos</h2>
            <div>
                <a class="btn btn-success" href="{{route('user.videos.create')}}">
                    <i class="fa-solid fa-upload"></i>
                    &nbsp;Import new video
                </a>
            </div>
        </div>
        <hr>
        <form class="my-4 d-flex gap-3 align-items-end" method="GET">
            <div class="col">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col">
                <label for="status" class="form-label fw-bold">Status</label>
                <select name="status" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($video_status as $status)
                        <option @if(($filters['status'] ?? null) === (string) $status['id']) selected @endif value="{{$status['id']}}">{{$status['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="category" class="form-label fw-bold">Category</label>
                <select name="category" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    <option @if(($filters['category'] ?? null) === 'without') selected @endif value="without">Without category</option>
                    @foreach($categories as $category)
                        <option @if(($filters['category'] ?? null) === (string) $category->id) selected @endif value="{{$category->id}}">{{$category->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="publication_date_start" class="form-label fw-bold">Publication date start</label>
                <input type="datetime-local" name="date[]" class="form-control" id="publication_date_start" value="{{$filters['date'][0] ?? null}}">
            </div>
            <div class="col">
                <label for="publication_date_end" class="form-label fw-bold">Publication date end</label>
                <input type="datetime-local" name="date[]" class="form-control" id="publication_date_end" value="{{$filters['date'][1] ?? null}}">
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
                <th scope="col" style="width: 40%">Video</th>
                <th scope="col">Visibility</th>
                <th scope="col">Publication date</th>
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
                    <td class="d-flex gap-3">
                        <a href="{{$video->route}}">
                            <img src="{{$video->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
                        </a>
                        <div>
                            <div>{{Str::limit($video->title, 100), '...'}}</div>
                            <small class="text-muted">{{Str::limit($video->description, 190), '...'}}</small>
                        </div>
                    </td>
                    <td class="align-middle">
                        @include('users.videos.partials.status')
                    </td>
                    <td class="align-middle">{{$video->publication_date->format('d F Y H:i')}}</td>
                    <td class="align-middle">{{$video->views_count}}</td>
                    <td class="align-middle">
                        @if($video->category)
                            <div class="badge bg-primary">
                                {{$video->category->title}}
                            </div>
                        @else
                            <div class="badge bg-secondary">
                                No category
                            </div>
                        @endif
                    </td>
                    <td class="align-middle">
                        <div class="">
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
                        </div>
                    </td>
                    <td class="align-middle">
                        @include('users.partials.interactions', ['item' => $video])
                        <div class="d-flex align-items-center gap-1">
                            <div class="badge bg-primary mt-2" data-bs-toggle="modal" data-bs-target="#video_likes" style="cursor: pointer;" data-id="{{$video->id}}">
                                Details
                            </div>
                            @if(!$video->show_likes)
                                <div class="badge bg-danger mt-2">
                                    Disabled
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="align-middle">
                        <a href="{{route('user.videos.edit', $video)}}" class="btn btn-primary btn-sm" title="Edit video">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            title="Delete video"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#delete_video"
                            data-title="{{$video->title}}"
                            data-views="{{$video->views_count}} views"
                            data-publication="Publish at {{$video->publication_date->format('d F Y')}}"
                            data-poster="{{$video->thumbnail_url}}"
                            data-route="{{route('user.videos.destroy', $video)}}"
                            data-alt="{{$video->title}} Thumbnail"
                            data-comments="{{$video->comments_count}}"
                            data-likes="{{$video->likes_count}}"
                            data-dislikes="{{$video->dislikes_count}}"
                            data-elements='{{json_encode(['title' => '', 'views' => '', 'publication' => '', 'poster' => 'src', 'route' => 'action', 'alt' => 'alt', 'comments' => '', 'likes' => '', 'dislikes' => ''])}}'
                        >
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <a href="{{route('user.videos.show', $video)}}" class="btn btn-success btn-sm" title="Video statistics">
                            <i class="fa-solid fa-chart-simple"></i>
                        </a>
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
        @include('users.videos.modals.delete')
        @include('users.videos.modals.likes')
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-upload fa-2x"></i>
                    <h5 class="my-3">Import your first video</h5>
                    <div class="text-muted my-3">Some description</div>
                    <a href="{{route('user.videos.create')}}" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                        Import
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
