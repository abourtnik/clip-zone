@extends('layouts.user')

@section('content')
    {{ Breadcrumbs::render('create_video') }}
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
        <h2>Create new video</h2>
    </div>
    <div class="card shadow-soft">
        <div class="card-body">
            <form action="{{ route('user.videos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required value="{{old('title')}}" maxlength="100">
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
                        <div class="form-text">Accepted formats : <strong>{{$accepted_video_formats}}</strong> - Max file size <strong>15 Mo</strong></div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="input-file">
                            <label for="thumbnail" class="rounded">
                                <i class="fas fa-upload"></i>
                                <div class="mt-2">Upload Video Poster</div>
                            </label>
                            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" required>
                        </div>
                        <div class="form-text">Accepted formats : <strong>{{$accepted_thumbnail_mimes_types}}</strong> - Max file size :  <strong>5 Mo</strong></div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="6" name="description" maxlength="5000">{{old('description')}}</textarea>
                </div>
                <div class="row" x-data="{ planned: {{old('status', 'false')}}}">
                    <div class="col-6 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" name="status" id="status" required x-model="planned">
                            @foreach($status as $s)
                                <option value="{{$s['id']}}" @if(old('status') == $s['id']) selected @endif>{{$s['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 mb-3" x-show="planned == {{\App\Enums\VideoStatus::PLANNED->value}}">
                        <label for="publication_date" class="form-label">Publication date</label>
                        <input
                            class="form-control"
                            type="datetime-local"
                            id="publication_date"
                            name="publication_date"
                            min="{{now()->toDateTimeLocalString('minute')}}"
                            value="{{ old('status') == \App\Enums\VideoStatus::PLANNED->value ? old('publication_date') : ''}}"
                            :required="planned == {{\App\Enums\VideoStatus::PLANNED->value}}"
                        >
                        <div class="form-text">The video remains private until it is published.</div>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{route('user.videos.index')}}" class="btn btn-secondary">
                        Cancel
                    </a>
                    <div class="d-flex gap-2">
                        <button value="create" type="submit" name="action" class="btn btn-success">
                            <i class="fa-solid fa-plus"></i>
                            Create & Add Another
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-plus"></i>
                            Create Video
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
