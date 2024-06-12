@use('App\Models\Video')
@use('App\Enums\VideoStatus')

@extends('layouts.user')

@section('title', 'Create video')

@section('content')
    {{ Breadcrumbs::render('create_video', $video) }}
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
    <form action="{{ route('user.videos.store', $video) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-xl-7 col-xxl-8 order-last order-xl-first">
                <div class="card shadow-soft h-100">
                    <div class="card-body" style="min-height: 912px">
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
                                value="{{old('title', $video->title)}}"
                                maxlength="{{config('validation.video.title.max')}}"
                                x-ref="title"
                                @keyup="count = $refs.title.value.length"
                            >
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <div class="form-text">A catchy title can help you hook viewers.</div>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.video.title.max')}}</span>
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
                                maxlength="{{config('validation.video.description.max')}}"
                                x-ref="description"
                                @keyup="count = $refs.description.value.length"
                            >{{old('description')}}</textarea>
                            <div class="d-flex flex-wrap align-items-center justify-content-between">
                                <div class="form-text">Writing descriptions with keywords can help viewers find your
                                    videos more easily through search.
                                </div>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.video.description.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-primary mt-3">Secondary</h5>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-sm-4 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" name="category_id" id="category">
                                    <option value="" selected>--- Select Category ---</option>
                                    @foreach($categories as $category)
                                        <option
                                            @selected(old('category_id') == $category->id) value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-4 mb-3">
                                <label for="language" class="form-label">Video language</label>
                                <select class="form-control" name="language" id="language">
                                    <option value="" selected>--- Select Language ---</option>
                                    @foreach($languages as $code => $language)
                                        <option @selected(old('language') == $code) value="{{$code}}">{{ucfirst($language)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="default_comments_sort" class="form-label">Default comments sort</label>
                                <select class="form-control" name="default_comments_sort" id="default_comments_sort">
                                    @foreach(['top', 'newest'] as $option)
                                        <option
                                            @selected(old('default_comments_sort') == $option) value="{{$option}}">{{Str::ucfirst($option)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h5 class="text-primary mt-3">Playlists</h5>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <label for="playlists" class="form-label d-none">Playlists</label>
                                <select id="playlists" name="playlists[]" multiple placeholder="Select Playlist..." autocomplete="off" class="select-multiple form-control">
                                    @foreach($playlists as $playlist)
                                        <option @selected(in_array($playlist->id, old('playlists', []))) value="{{$playlist->id}}">{{Str::limit($playlist->title, 40)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-xxl-4 order-first order-xl-last mb-4 mb-xl-0">
                <div class="card shadow-soft h-100">
                    <div class="card-body">
                        <div
                            class="ratio ratio-16x9"
                            x-data="{loading: false, video: {{$video->is_uploading ? 'false' : json_encode(['url' => $video->file_url])}} }"
                            @uploaded.window="loading=true"
                            @terminate.window="loading= false; video = $event.detail"
                        >
                            <template x-if="!video">
                                <div class="bg-light-dark border border-light d-flex justify-content-center align-items-center">
                                    <div class="text-center">
                                        <div class="mb-2">Your video is processing ...</div>
                                        <div class="text-muted text-sm">You will receive a notification once the processing is complete.</div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="loading">
                                <div class="bg-light-dark border border-light d-flex justify-content-center align-items-center">
                                    <div class="text-center">
                                        <div class="mb-4 fw-bold text-danger">Your video is almost ready ...</div>
                                        <div class="spinner-border text-danger" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <template x-if="video">
                                <video controls class="w-100 border" controlsList="nodownload" onloadstart="this.volume=0.5">
                                    <source x-bind:src="video.url" type="{{Video::MIMETYPE}}">
                                </video>
                            </template>
                        </div>
                        <div class="bg-light border mt-2 px-3 py-2 d-flex flex-column gap-3">
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted fw-bold">Video Link</small>
                                    <button
                                        x-data
                                        @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        title="Copy video link"
                                        data-link="{{$video->route}}"
                                        data-bs-toggle="tooltip"
                                        data-bs-trigger="click"
                                        data-bs-title="Link copied !"
                                    >
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                </div>
                                <a class="text-decoration-none text-sm" href="{{$video->route}}">{{$video->route}}</a>
                            </div>
                            <div>
                                <small class="text-muted fw-bold mb-2">Original file name</small>
                                <div class="text-sm">{{$video->original_file_name}}</div>
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
                        <h6 class="text-primary mt-4">Visibility</h6>
                        <hr class="mt-2">
                        <div>
                            <div id="planned_value" class="d-none">{{VideoStatus::PLANNED->value}}</div>
                            <div class="row" x-data="planned({{ json_encode(old('status') == VideoStatus::PLANNED->value)}})">
                                <div class="col-6">
                                    <label for="status" class="form-label d-none">Visibility</label>
                                    <select class="form-control" name="status" id="status" required @change="update">
                                        @foreach($status as $id => $name)
                                            <option @selected(old('status') == $id) value="{{$id}}">{{$name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6" x-show="value">
                                    <label for="scheduled_date" class="form-label d-none">Scheduled date</label>
                                    <input
                                        class="form-control"
                                        type="datetime-local"
                                        id="scheduled_date"
                                        name="scheduled_date"
                                        min="{{now()->toDateTimeLocalString('minute')}}"
                                        :required="value"
                                        x-model="date"
                                    >
                                </div>
                            </div>
                        </div>
                        <h6 class="text-primary mt-4">Thumbnail *</h6>
                        <hr class="mt-2">
                        <div class="text-sm text-muted mb-3">Select or upload a picture that shows what's in your video. A good thumbnail stands out and draws viewers' attention.</div>
                        <thumbnails-select data={{$thumbnails}}></thumbnails-select>
                        <h6 class="text-primary mt-4">Comments and ratings</h6>
                        <hr class="mt-2">
                        <div class="d-flex flex-column gap-1">
                            <div class="form-check form-switch">
                                <input type="hidden" value="0" name="allow_comments">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="allow_comments"
                                    value="1"
                                    name="allow_comments"
                                    @checked(!old() || old('allow_comments'))
                                >
                                <label class="form-check-label" for="allow_comments">
                                    Allow comments on videos
                                </label>
                            </div>
                            <div class="form-check form-switch">
                                <input type="hidden" value="0" name="show_likes">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="show_likes"
                                    value="1"
                                    name="show_likes"
                                    @checked(!old() || old('show_likes'))
                                >
                                <label class="form-check-label" for="show_likes">
                                    Show how many viewers like this video
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <a href="{{route('user.videos.index')}}" class="btn btn-primary">
                Complete Later
            </a>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-plus"></i>
                    Create Video
                </button>
            </div>
        </div>
    </form>
    @include('users.partials.crop')
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            const planned_value = document.getElementById('planned_value').textContent;
            Alpine.data('planned', (initial) => ({
                value: initial,
                date: initial ? '{{old('scheduled_date')}}' : '',
                update(e) {
                    this.value = e.target.options[e.target.selectedIndex].index == planned_value;
                }
            }));
        })

        document.addEventListener("DOMContentLoaded", function(event) {
            window.PRIVATE_CHANNEL.listen('.video.uploaded', (data) => {

                window.dispatchEvent(new CustomEvent('uploaded'));

                setTimeout(() => {
                    window.dispatchEvent(new CustomEvent('terminate', {detail: data}));
                }, 4000)

            });
        })
    </script>
@endpush

