@use('App\Models\Video')

@extends('layouts.user')

@section('title', 'Create Subtitle')

@section('content')
    {{ Breadcrumbs::render('create_subtitle', $video) }}
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
        <h2>Create new subtitle</h2>
    </div>
    <form action="{{ route('user.videos.subtitles.store', $video) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-7 col-xxl-8 order-last order-xl-first">
                <div class="card shadow-soft h-100">
                    <div class="card-body" x-data="{language: '{{old('name')}}' }">
                        <h5 class="text-primary">Details</h5>
                        <hr>
                        <div class="col-12 mb-3">
                            <label for="language" class="form-label">Language</label>
                            <select class="form-control" name="language" id="language" @change="language = $event.target.options[$event.target.selectedIndex].text" required>
                                <option value="" selected>--- Select Language ---</option>
                                @foreach($languages as $code => $language)
                                    <option @selected(old('language') == $code) value="{{$code}}">{{ucfirst($language)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.name.value.length">
                            <label for="name" class="form-label">Name</label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                required
                                :value="language"
                                maxlength="{{config('validation.subtitle.name.max')}}"
                                x-ref="name"
                                @keyup="count = $refs.name.value.length"
                            >
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <div class="form-text">Choose the name which will be displayed on the video player</div>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.subtitle.name.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control" name="status" id="status" required>
                                @foreach($status as $id => $name)
                                    <option @selected(old('status') == $id) value="{{$id}}">{{$name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <h5 class="text-primary mt-4">File</h5>
                        <hr>
                        <div class="col-12 mb-3">
                            <div class="alert alert-info">
                                <p>
                                    A subtitle or closed caption file contains the text of what is said in the video.
                                    It also contains time codes for when each line of text should be displayed.
                                    Some files also include position and style info, which is especially useful for deaf or hard of
                                    hearing viewers.
                                </p>
                                <p class="fw-bold">
                                    We only support WebVTT format for now.
                                </p>
                                <p>
                                    For more information about WebVTT format please consult this <a class="alert-link" target="_blank" href="https://developer.mozilla.org/en-US/docs/Web/API/WebVTT_API">link</a>
                                </p>

                            </div>
                            <label class="file-button" x-data="{file:null}">
                                <input type="file" name="file" @change="file = $event.target.files[0]" accept="text/vtt" />
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="fa fa-upload"></i>
                                    <span x-show="!file">Upload subtitle file</span>
                                    <span x-show="file" x-text="file?.name"></span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-xxl-4 order-first order-xl-last mb-4 mb-xl-0">
                <div class="card shadow-soft h-100">
                    <div class="card-body">
                        <div class="ratio ratio-16x9">
                            <video controls class="w-100 border" controlsList="nodownload" onloadstart="this.volume=0.5">
                                <source src="{{$video->file_url}}" type="{{Video::MIMETYPE}}">
                            </video>
                        </div>
                        <div class="bg-light border mt-2 px-3 py-2 d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="col-11">
                                    <small class="text-muted fw-bold mb-2">Video Link</small>
                                    <a class="text-decoration-none d-block text-sm" href="{{$video->route}}">{{$video->route}}</a>
                                </div>
                                <button
                                    x-data
                                    @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                                    type="button"
                                    class="btn btn-sm btn-light col-1"
                                    title="Copy video link"
                                    data-link="{{$video->route}}"
                                    data-bs-toggle="tooltip"
                                    data-bs-title="Link copied !"
                                    data-bs-trigger="click"
                                >
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                            <div>
                                <small class="text-muted fw-bold mb-2">Video title</small>
                                <div class="text-sm">{{$video->title}}</div>
                            </div>
                            <div>
                                <small class="text-muted fw-bold mb-2">File size</small>
                                <div class="text-sm">@size($video->size)</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted fw-bold mb-2">Video ID</small>
                                    <div class="text-sm">{{$video->uuid}}</div>
                                </div>
                                <button
                                    x-data
                                    @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                                    type="button"
                                    class="btn btn-sm btn-light"
                                    title="Copy video ID"
                                    data-link="{{$video->uuid}}"
                                    data-bs-toggle="tooltip"
                                    data-bs-trigger="click"
                                    data-bs-title="ID copied !"
                                >
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <a href="{{route('user.videos.subtitles.index', $video)}}" class="btn btn-secondary">
                Cancel
            </a>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i>
                    Create Subtitle
                </button>
            </div>
        </div>
    </form>
@endsection
