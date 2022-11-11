@extends('layouts.user')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mt-5">
            Merci de corriger les erreurs suivantes:
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Edit video {{$video->title}}</h2>
    </div>
    <div class="card shadow-soft">
        <div class="card-body">
            <form action="{{ route('user.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required value="{{$video->title}}">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="poster" class="form-label">Video Poster</label>
                        <input class="form-control" type="file" id="poster" name="poster">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="6" name="description">{{$video->description}}</textarea>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-pen"></i>
                    Update
                </button>
            </form>
        </div>
    </div>
@endsection
