@extends('layouts.user')

@section('title', 'Video details')

@section('content')
    {{ Breadcrumbs::render('edit_video', $video) }}
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
        <h2>Video details</h2>
    </div>
    <form action="{{ route('user.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-xl-7 col-xxl-8 order-last order-xl-first">
                <div class="card shadow-soft h-100">
                    <div class="card-body" style="min-height: 875px">
                        <h5 class="text-primary">Details</h5>
                        <hr>
                        <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.title.value.length">
                            <label for="title" class="form-label">Title</label>
                            <input
                                maxlength="{{config('validation.video.title.max')}}"
                                type="text"
                                class="form-control"
                                id="title"
                                name="title"
                                required
                                value="{{old('title', $video->title)}}"
                                x-ref="title"
                                @keyup="count = $refs.title.value.length">
                            <div class="d-flex align-items-center justify-content-between">
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
                                maxlength="{{config('validation.video.description.max')}}"
                                name="description"
                                x-ref="description"
                                @keyup="count = $refs.description.value.length"
                            >{{old('description', $video->description)}}</textarea>
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.video.description.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-primary mt-3">Secondary</h5>
                        <hr>
                        <div class="row">
                            <div class="col-4 mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" name="category_id" id="category">
                                    <option value="" selected>--- Select Category ---</option>
                                    @foreach($categories as $category)
                                        <option @selected(old('category_id', $video->category_id) == $category->id) value="{{$category->id}}">{{$category->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4 mb-3">
                                <label for="language" class="form-label">Video language</label>
                                <select class="form-control" name="language" id="language">
                                    <option value="" selected>--- Select Language ---</option>
                                    @foreach($languages as $code => $language)
                                        <option @selected(old('language', $video->language?->value) == $code) value="{{$code}}">{{$language}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <label for="default_comments_sort" class="form-label">Default comments sort</label>
                                <select class="form-control" name="default_comments_sort" id="default_comments_sort">
                                    @foreach(['top', 'newest'] as $option)
                                        <option @selected(old('default_comments_sort', $video->default_comments_sort) == $option) value="{{$option}}">{{Str::ucfirst($option)}}</option>
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
                                        <option @selected(old() ? in_array($playlist->id, old('playlists', [])) : $playlist->has_video) value="{{$playlist->id}}">{{Str::limit($playlist->title, 40)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-5 col-xxl-4 order-first order-xl-last mb-4 mb-xl-0">
                <div class="card shadow-soft">
                    <div class="card-body">
                        <video controls class="w-100 border" controlsList="nodownload" poster="{{$video->thumbnail_url}}">
                            <source src="{{route('video.file', $video)}}" type="{{$video->mimetype}}">
                        </video>
                        <div class="bg-light mt-2 px-3 py-2 d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted fw-bold mb-2">Video Link</small>
                                    <a class="text-decoration-none d-block text-sm w-75" href="{{$video->route}}">{{$video->route}}</a>
                                </div>
                                <button
                                    x-data
                                    @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                                    type="button"
                                    class="btn btn-sm btn-light"
                                    title="Copy video link"
                                    data-link="{{$video->route}}"
                                    data-bs-toggle="tooltip"
                                    data-bs-title="Copy video link"
                                >
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                            <div>
                                <small class="text-muted fw-bold mb-2">Original file name</small>
                                <div class="text-sm">{{$video->original_file_name}}</div>
                            </div>
                        </div>
                        <h6 class="text-primary mt-4">Visibility</h6>
                        <hr class="mt-2">
                        <div>
                            <div id="planned_value" class="d-none">{{\App\Enums\VideoStatus::PLANNED->value}}</div>
                            <div class="row" x-data="planned({{ json_encode(old('status', $video->real_status->value) == \App\Enums\VideoStatus::PLANNED->value)}})">
                                <div class="col-6">
                                    <label for="status" class="form-label d-none">Visibility</label>
                                    <select class="form-control" name="status" id="status" required @change="update">
                                        @foreach($status as $id => $name)
                                            <option @selected(old('status', $video->real_status->value) == $id) value="{{$id}}">
                                                {{$name}}
                                            </option>
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
                        <h6 class="text-primary mt-4">Thumbnail</h6>
                        <hr class="mt-2">
                        <image-upload source="{{$video->thumbnail_url}}" name="thumbnail"></image-upload>
                        <div class="form-text text-sm">
                            <div>Accepted formats : <strong>{{$accepted_thumbnail_mimes_types}}</strong> • Max file size :  <strong>2 Mo</strong></div>
                        </div>
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
                                    @checked(old('allow_comments', $video->allow_comments))
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
                                    @checked(old('show_likes', $video->show_likes))
                                >
                                <label class="form-check-label" for="show_likes">
                                    Show likes and dislikes count on video
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <span>Update Video</span>
                </button>
            </div>
        </div>
    </form>
    @include('users.partials.crop')
@endsection

<script>
    document.addEventListener('alpine:init', () => {
        const planned_value = document.getElementById('planned_value').textContent;
        Alpine.data('planned', (initial) => ({
            value: initial,
            date: initial ? '{{old('scheduled_date', $video->scheduled_date)}}' : '',
            update(e) {
                this.value = e.target.options[e.target.selectedIndex].index == planned_value;
            }
        }));
    })
</script>
