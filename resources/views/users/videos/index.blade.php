@extends('layouts.user')

@section('content')
    @if($videos->total())
    {{ Breadcrumbs::render('videos') }}
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Videos</h2>
            <div>
                <a class="btn btn-primary" href="{{route('user.videos.create')}}">
                    <i class="fa-solid fa-plus"></i>
                    New
                </a>
            </div>
        </div>
        <hr>
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th scope="col" class="w-50">Video</th>
                <th scope="col">Status</th>
                <th scope="col">Publication date</th>
                <th scope="col">Views</th>
                <th scope="col">Comments</th>
                <th scope="col">Interactions</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($videos as $video)
                <tr class="bg-light">
                    <td class="d-flex gap-3">
                        <a href="{{route('video.show', $video)}}">
                            <img src="{{$video->poster_url}}" alt="" style="width: 120px;height: 68px">
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
                        <div class="d-flex gap-3">
                            @if($video->comments_count)
                                <div class="badge bg-info">
                                    {{trans_choice('comments', $video->comments_count)}}
                                </div>
                            @else
                                <div class="badge bg-secondary">
                                    No comments
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="align-middle">
                        @include('users.partials.interactions', ['item' => $video])
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
                            data-poster="{{$video->poster_url}}"
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
            @endforeach
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
