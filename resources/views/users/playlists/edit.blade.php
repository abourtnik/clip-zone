@extends('layouts.user')

@section('title', 'Playlist detail')

@section('content')
    {{ Breadcrumbs::render('edit_playlist', $playlist) }}
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <p class="fw-bold">Oups some fields are incorrect</p>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Playlist detail</h2>
    </div>
    <form action="{{ route('user.playlists.update', $playlist) }}" method="POST">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-xl-7 order-last order-xl-first">
                <div class="card shadow-soft">
                    <div class="card-body">
                        <h5 class="text-primary">Details</h5>
                        <hr>
                        <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.title.value.length">
                            <label for="title" class="form-label">Title</label>
                            <input
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                required
                                value="{{old('title', $playlist->title)}}"
                                maxlength="{{config('validation.playlist.title.max')}}"
                                x-ref="title"
                                @keyup="count = $refs.title.value.length"
                            >
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-text">A catchy title can help you hook viewers.</div>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.playlist.title.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.description.value.length">
                            <label for="description" class="form-label">Description</label>
                            <textarea
                                class="form-control"
                                id="description"
                                rows="15"
                                name="description"
                                maxlength="{{config('validation.playlist.description.max')}}"
                                x-ref="description"
                                @keyup="count = $refs.description.value.length"
                            >{{old('description', $playlist->description)}}</textarea>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-text">Writing descriptions with keywords can help viewers find your
                                    videos more easily through search.
                                </div>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.playlist.description.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="status" class="form-label">Visibility</label>
                            <select class="form-control" name="status" id="status" required @change="update">
                                @foreach($status as $id => $name)
                                    <option @selected(old('status', $playlist->status->value) == $id) value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 order-first order-xl-last mb-4 mb-xl-0">
                <playlist-videos initial="{{$videos}}"/>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <a href="{{route('user.videos.index')}}" class="btn btn-secondary">
                Cancel
            </a>
            <div class="d-flex gap-2">
                <button value="save" type="submit" name="action" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-pen"></i>
                    <span>Update & Continue Editing</span>
                </button>
                <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="fa-solid fa-pen"></i>
                    <span>Update Playlist</span>
                </button>
            </div>
        </div>
    </form>
@endsection
