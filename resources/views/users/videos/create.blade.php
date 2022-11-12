@extends('layouts.user')

@section('content')
    {{ Breadcrumbs::render('create_video') }}
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
        <h2>Create new video</h2>
    </div>
    <div class="card shadow-soft">
        <div class="card-body">
            <form action="{{ route('user.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required value="{{old('title')}}">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="input-file">
                            <label for="file" class="rounded">
                                <i class="fas fa-upload"></i>
                                <div class="mt-2">Upload Video File</div>
                            </label>
                            <input type="file" name="file" id="file" required>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="input-file">
                            <label for="poster" class="rounded">
                                <i class="fas fa-upload"></i>
                                <div class="mt-2">Upload Video Poster</div>
                            </label>
                            <input type="file" name="poster" id="poster" required>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="3" name="description">{{old('description')}}</textarea>
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" name="status_id" id="status" required>
                            @foreach($status as $s)
                                <option value="{{$s['id']}}">{{$s['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 mb-3">
                        <label for="publication_date" class="form-label">Publication date</label>
                        <input class="form-control" type="datetime-local" id="publication_date" required name="publication_date" value="{{old('publication_date')}}">
                        <div class="form-text">The video remains private until it is published.</div>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        <i class="fa-solid fa-plus"></i>
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
