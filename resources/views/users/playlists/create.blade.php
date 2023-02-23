@extends('layouts.user')

@section('title', 'Create playlist')

@section('content')
    {{ Breadcrumbs::render('create_playlist') }}
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
        <h2>Create new playlist</h2>
    </div>
    <form action="{{ route('user.playlists.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-6 order-last order-xl-first">
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
                                value="{{old('title')}}"
                                maxlength="100"
                                x-ref="title"
                                @keyup="count = $refs.title.value.length"
                            >
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-text">A catchy title can help you hook viewers.</div>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>100</span>
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
                                maxlength="5000"
                                x-ref="description"
                                @keyup="count = $refs.description.value.length"
                            >{{old('description')}}</textarea>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-text">Writing descriptions with keywords can help viewers find your
                                    videos more easily through search.
                                </div>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>5000</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="status" class="form-label">Visibility</label>
                            <select class="form-control" name="status" id="status" required @change="update">
                                @foreach($status as $id => $name)
                                    <option @selected(old('status') == $id) value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 order-first order-xl-last mb-4 mb-xl-0">
                <div class="card shadow-soft">
                    <div class="card-body">
                        <h5 class="text-primary">Add Videos</h5>
                        <hr>
                        <playlist-videos/>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <a href="{{route('user.playlists.index')}}" class="btn btn-secondary">
                Cancel
            </a>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i>
                    Create Playlist
                </button>
            </div>
        </div>
    </form>
@endsection
