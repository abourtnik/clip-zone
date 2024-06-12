@use('App\Filters\Forms\User\VideoFiltersForm')

@extends('layouts.user')

@section('title', 'Channel videos')

@section('content')
    @if($videos->total() || request()->all())
    <div x-data="{selected:[]}">
        {{ Breadcrumbs::render('videos') }}
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="mb-0">My Videos</h2>
            <div class="d-flex align-items-center gap-1">
                <button
                    x-show.important="selected.length > 0"
                    class="btn btn-danger d-flex align-items-center gap-1"
                    data-bs-toggle="modal"
                    data-bs-target="#mass_delete"
                    type="button"
                >
                    <i class="fa-solid fa-trash me-1"></i>
                    <span>Delete</span>
                    <span x-text="selected.length"></span>
                    <span x-text="selected.length === 1 ? 'video' : 'videos'"></span>
                </button>
                <button class="btn btn-success d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#video_create">
                    <i class="fa-solid fa-upload"></i>
                    &nbsp;<span>Import new video</span>
                </button>
            </div>
        </div>
        <hr>
        {!! form(FormBuilder::create(VideoFiltersForm::class)) !!}
        <form
            action="{{route('user.videos.mass-delete')}}"
            method="POST"
            x-ref="deleteForm"
        >
            @csrf
            @method('DELETE')
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col"></th>
                    <th scope="col" class="w-40" style="min-width: 400px">Video</th>
                    <th scope="col">Status</th>
                    <th scope="col" style="min-width: 180px;">Date</th>
                    <th scope="col">Views</th>
                    <th scope="col">Category</th>
                    <th scope="col">Comments</th>
                    <th scope="col" style="min-width: 170px;">Interactions</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($videos as $video)
                    <tr class="bg-light">
                        <td class="align-middle">
                            <div class="form-check">
                                <input
                                    name="ids[]"
                                    class="form-check-input"
                                    type="checkbox"
                                    x-model="selected"
                                    value="{{$video->id}}"
                                    id="video-{{$video->id}}"
                                >
                                <label class="form-check-label" for="video-{{$video->id}}"></label>
                            </div>
                        </td>
                        <td class="d-flex gap-3">
                            <a href="{{$video->route}}">
                                @include('users.videos.partials.thumbnail')
                            </a>
                            <div class="d-flex flex-column align-items-start gap-2">
                                <div>{{Str::limit($video->title, 100), '...'}}</div>
                                <div class="text-muted text-sm">{{Str::limit($video->description, 75), '...'}}</div>
                                <div class="badge bg-primary w-auto">{{$video->duration}} - @size($video->size)</div>
                            </div>
                        </td>
                        <td class="align-middle">
                            @include('videos.status')
                        </td>
                        <td class="align-middle">
                            @if($video->is_private || $video->is_unlisted || $video->is_draft|| $video->is_failed)
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
                            @if($video->is_created)
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
                            @if($video->is_created)
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
                            @if($video->is_created)
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
                            @if($video->is_created)
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
                                @can('update', $video)
                                    @if($video->is_draft)
                                        <a href="{{route('user.videos.create', $video)}}" class="btn btn-primary btn-sm" title="Edit draft">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    @else
                                        <a href="{{route('user.videos.edit', $video)}}" class="btn btn-primary btn-sm" title="Edit video">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                    @endif
                                @endcan
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
                                @if($video->is_created)
                                    <a href="{{route('user.videos.show', $video)}}" class="btn btn-success btn-sm" title="Video statistics">
                                        <i class="fa-solid fa-chart-simple"></i>
                                    </a>
                                @endif
                                @can('pin', $video)
                                    <form action="{{route('user.videos.pin', $video)}}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button class="btn btn-secondary btn-sm" title="Pin video" type="submit">
                                            <i class="fa-solid fa-thumbtack"></i>
                                        </button>
                                    </form>
                                @elsecan('unpin', $video)
                                    <form action="{{route('user.videos.unpin', $video)}}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button class="btn btn-secondary btn-sm" title="Unpin video" type="submit">
                                            <i class="fa-solid fa-link-slash"></i>
                                        </button>
                                    </form>
                                @endcan
                                @can('download', $video)
                                    <a download href="{{route('video.download', $video)}}" class="btn btn-dark btn-sm" title="Download video">
                                        <i class="fa-solid fa-download"></i>
                                    </a>
                                @endcan
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
        </form>
        {{ $videos->links() }}
        @include('users.videos.modals.delete')
        @include('videos.modals.interactions')
        @include('users.videos.modals.mass-delete')
    </div>
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-upload fa-2x"></i>
                    <h5 class="my-3">Import your first video</h5>
                    <div class="text-muted my-3">
                        Ready to share your talents with the world ?
                    </div>
                    <button class="btn btn-success btn" data-bs-toggle="modal" data-bs-target="#video_create">
                        <i class="fa-solid fa-plus"></i>
                        Import
                    </button>
                </div>
            </div>
        </div>
    @endif
@endsection
