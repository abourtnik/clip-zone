@extends('layouts.user')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>My Videos</h2>
        <div>
            <a class="btn btn-outline-success" href="#">
                <i class="fa-solid fa-file-export"></i>
                Export
            </a>
            <a class="btn btn-primary" href="{{route('user.videos.create')}}">
                <i class="fa-solid fa-plus"></i>
                New
            </a>
        </div>
    </div>
    <hr>
    <table class="table table-bordered">
        <thead>
        <tr style="border-top: 3px solid #0D6EFD;">
            <th scope="col">#</th>
            <th scope="col">Video</th>
            <th scope="col">Title</th>
            <th scope="col">Duration</th>
            <th scope="col">Views</th>
            <th scope="col">Created</th>
            <th scope="col">Updated</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($videos as $video)
            <tr class="bg-light">
                <th scope="row">{{$video->id}}</th>
                <td>{{$video->file}}</td>
                <td>{{Str::limit($video->title, 20), '...'}}</td>
                <td>{{$video->duration}}</td>
                <td>{{$video->views}}</td>
                <td>{{$video->created_at->format('d F Y - H:i:s')}}</td>
                <td>{{$video->updated_at->format('d F Y - H:i:s')}}</td>
                <td>
                    <a href="{{route('user.videos.edit', $video)}}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="" class="btn btn-danger btn-sm">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $videos->links() }}
@endsection
