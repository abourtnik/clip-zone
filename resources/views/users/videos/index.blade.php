@extends('layouts.user')

@section('content')
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
                    <a href="{{route('pages.video', $video)}}">
                        <img src="{{$video->poster_url}}" alt="" style="width: 120px;height: 68px">
                    </a>
                    <div>
                        <div>{{Str::limit($video->title, 100), '...'}}</div>
                        <small class="text-muted">{{Str::limit($video->description, 190), '...'}}</small>
                    </div>
                </td>
                <td>
                    @if($video->isActive())
                        <span class="badge bg-success">
                            <i class="fa-solid fa-eye"></i>&nbsp;
                            Public
                        </span>
                    @elseif($video->isPlanned())
                        <span class="badge bg-warning">
                            <i class="fa-solid fa-clock"></i>&nbsp;
                            Planned
                        </span>
                    @else
                        <span class="badge bg-danger">
                            <i class="fa-solid fa-eye-slash"></i>&nbsp;
                            Private
                        </span>
                    @endif
                </td>
                <td>{{$video->publication_date->format('d F Y H:i')}}</td>
                <td>{{$video->views}}</td>
                <td>{{$video->comments()->count()}}</td>
                <td>{{$video->likes()->count()}}</td>
                <td>
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
                        data-likes="{{$video->comments()->count()}}"
                        data-dislikes="{{$video->comments()->count()}}"
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
@endsection
