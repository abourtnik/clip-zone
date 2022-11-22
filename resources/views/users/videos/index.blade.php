@extends('layouts.user')

@section('content')
    {{ Breadcrumbs::render('videos') }}
    @if($videos->count())
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
                        <a href="{{route('pages.video', $video)}}">
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
                    <td class="align-middle">{{$video->views}}</td>
                    <td class="align-middle">
                        <div class="d-flex gap-3">
                            <div>{{$video->comments()->count()}}</div>
                            <a class="link-primary text-decoration-none" href="{{route('user.comments')}}">Voir</a>
                        </div>

                    </td>
                    <td class="align-middle">
                        <a href="#likes" data-bs-toggle="modal" data-bs-target="#video_likes" class="text-decoration-none text-black">
                            <div class="d-flex gap-2 justify-content-center mb-3">
                                <div>
                                    <i class="fa-regular fa-thumbs-up"></i>
                                    {{$video->likes()->count()}}
                                </div>
                                <div>
                                    <i class="fa-regular fa-thumbs-down"></i>
                                    {{$video->dislikes()->count()}}
                                </div>
                            </div>
                            <div class="progress border border-primary">
                                <div
                                    class="progress-bar bg-success"
                                    role="progressbar"
                                    aria-label="Likes ratio"
                                    style="width: {{$video->likes_ratio}}%;"
                                    aria-valuenow="{{$video->likes_ratio}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{$video->likes_ratio}}%
                                </div>
                                <div
                                    class="progress-bar bg-danger"
                                    role="progressbar"
                                    aria-label="Dislikes ratio"
                                    style="width: {{$video->dislikes_ratio}}%"
                                    aria-valuenow="{{$video->dislikes_ratio}}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                    {{$video->dislikes_ratio}}%
                                </div>
                            </div>
                        </a>
                    </td>
                    <td class="align-middle">
                        <a href="{{route('user.videos.edit', $video)}}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <button
                            type="button"
                            class="btn btn-danger btn-sm"
                            data-bs-toggle="modal"
                            data-bs-target="#delete_video"
                            data-title="{{$video->title}}"
                            data-views="{{$video->views}} views"
                            data-publication="Publish at {{$video->publication_date->format('d F Y')}}"
                            data-poster="{{$video->poster_url}}"
                            data-route="{{route('user.videos.destroy', $video)}}"
                            data-alt="{{$video->title}} Thumbnail"
                            data-comments="{{$video->comments()->count()}}"
                            data-likes="{{$video->likes()->count()}}"
                            data-dislikes="{{$video->dislikes()->count()}}"
                            data-elements='{{json_encode(['title' => '', 'views' => '', 'publication' => '', 'poster' => 'src', 'route' => 'action', 'alt' => 'alt', 'comments' => '', 'likes' => '', 'dislikes' => ''])}}'
                        >
                            <i class="fa-solid fa-trash"></i>
                        </button>
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
