@use('App\Models\Video')
@use('App\Enums\VideoStatus')
@use('Diglactic\Breadcrumbs\Breadcrumbs')

@extends('layouts.user')

@section('title', __('Video details'))

@section('content')
    {{ Breadcrumbs::render('edit_video', $video) }}
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <p class="fw-bold">{{ __('Oups some fields are incorrect') }}</p>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>{{ __('Video details') }}</h2>
    </div>
    <form action="{{ route('user.videos.update', $video) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="row">
            <div class="col-xl-7 col-xxl-8 order-last order-xl-first">
                <div class="card shadow-soft h-100">
                    <div class="card-body" style="min-height: 875px">
                        <h5 class="text-primary">{{ __('Details') }}</h5>
                        <hr>
                        <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.title.value.length">
                            <label for="title" class="form-label">{{ __('Title') }}</label>
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
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.video.title.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.description.value.length">
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea
                                class="form-control"
                                id="description"
                                rows="15"
                                maxlength="{{config('validation.video.description.max')}}"
                                name="description"
                                x-ref="description"
                                @keyup="count = $refs.description.value.length"
                            >{{old('description', $video->description)}}</textarea>
                            <div class="d-flex flex-wrap align-items-center justify-content-end">
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.video.description.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <h5 class="text-primary mt-3">{{ __('Secondary') }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-sm-4 mb-3">
                                <label for="category" class="form-label">{{ __('Category') }}</label>
                                <select class="form-control" name="category_id" id="category">
                                    <option value="" selected>--- {{ __('Select Category') }} ---</option>
                                    @foreach($categories as $category)
                                        <option @selected(old('category_id', $video->category_id) == $category->id) value="{{$category->id}}">{{__($category->title)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-4 mb-3">
                                <label for="language" class="form-label">Video language</label>
                                <select class="form-control" name="language" id="language">
                                    <option value="" selected>--- {{ __('Select Language') }} ---</option>
                                    @foreach($languages as $code => $language)
                                        <option @selected(old('language', $video->language) == $code) value="{{$code}}">{{ucfirst($language)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-4">
                                <label for="default_comments_sort" class="form-label">{{ __('Default comments sort') }}</label>
                                <select class="form-control" name="default_comments_sort" id="default_comments_sort">
                                    @foreach(['top' => "Top comments", 'newest' => 'Newest first'] as $value => $label)
                                        <option @selected(old('default_comments_sort', $video->default_comments_sort) == $value) value="{{$value}}">{{__($label)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h5 class="text-primary mt-3">{{ __('Playlists') }}</h5>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <label for="playlists" class="form-label d-none">{{ __('Playlists') }}</label>
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
                        <div class="ratio ratio-16x9">
                            @if($video->is_uploading)
                                <div class="bg-light-dark border border-light d-flex justify-content-center align-items-center">
                                    <div class="text-center">
                                        <div class="mb-2">{{ __('Your video is processing ...') }}</div>
                                        <div class="text-muted text-sm">{{ __('You will receive a notification once the processing is complete') }}</div>
                                    </div>
                                </div>
                            @else
                                <video controls class="w-100 border" controlsList="nodownload" poster="{{$video->thumbnail_url}}" onloadstart="this.volume=0.5">
                                    <source src="{{$video->file_url}}" type="{{Video::MIMETYPE}}">
                                </video>
                            @endif
                        </div>
                        <div class="bg-light border mt-2 px-3 py-2 d-flex flex-column gap-3">
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted fw-bold">{{ __('Video Link') }}</small>
                                    <button
                                        x-data
                                        @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        title="{{ __('Copy video link') }}"
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
                                <small class="text-muted fw-bold mb-2">{{ __('Original file name') }}</small>
                                <div class="text-sm">{{$video->original_file_name}}</div>
                            </div>
                            <div>
                                <small class="text-muted fw-bold mb-2">{{ __('File size') }}</small>
                                <div class="text-sm">@size($video->size)</div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted fw-bold mb-2">{{ __('Video ID') }}</small>
                                    <div class="text-sm">{{$video->uuid}}</div>
                                </div>
                                <button
                                    x-data
                                    @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                                    type="button"
                                    class="btn btn-sm btn-light"
                                    title="{{ __('Copy video ID') }}"
                                    data-link="{{$video->uuid}}"
                                    data-bs-toggle="tooltip"
                                    data-bs-trigger="click"
                                    data-bs-title="{{ __('ID copied !') }}"
                                >
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <h6 class="text-primary mt-4">{{ __('Visibility') }}</h6>
                        <hr class="mt-2">
                        <div>
                            <div id="planned_value" class="d-none">{{VideoStatus::PLANNED->value}}</div>
                            <div class="row" x-data="planned({{ json_encode(old('status', $video->real_status->value) == VideoStatus::PLANNED->value)}})">
                                <div class="col-6">
                                    <label for="status" class="form-label d-none">{{ __('Visibility') }}</label>
                                    <select class="form-control" name="status" id="status" required @change="update">
                                        @foreach($status as $id => $name)
                                            <option @selected(old('status', $video->real_status->value) == $id) value="{{$id}}">
                                                {{ __($name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6" x-show="value">
                                    <label for="scheduled_at" class="form-label d-none">{{ __('Scheduled At') }}</label>
                                    <input
                                        class="form-control"
                                        type="datetime-local"
                                        id="scheduled_at"
                                        name="scheduled_at"
                                        min="{{now()->toDateTimeLocalString('minute')}}"
                                        :required="value"
                                        x-model="date"
                                    >
                                </div>
                            </div>
                        </div>
                        <h6 class="text-primary mt-4">{{ __('Thumbnail') }}</h6>
                        <hr class="mt-2">
                        <div class="text-sm text-muted mb-3">{{ __('Select or upload a picture that shows what\'s in your video. A good thumbnail stands out and draws viewers attention') }}</div>
                        <thumbnails-select video="{{$video->id}}"></thumbnails-select>
                        <h6 class="text-primary mt-4">{{ __('Comments and ratings') }}</h6>
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
                                    {{ __('Allow comments on this video') }}
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
                                    {{ __('Show how many viewers like this video') }}
                                </label>
                            </div>
                        </div>
                        <h6 class="text-primary mt-4">{{ __('Ad management') }}</h6>
                        <hr class="mt-2">
                        <div class="d-flex flex-column gap-1">
                            <div class="form-check form-switch">
                                <input type="hidden" value="0" name="show_ad">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="show_ad"
                                    value="1"
                                    name="show_ad"
                                    @checked(old('show_ad', $video->show_ad))
                                >
                                <label class="form-check-label" for="show_ad">
                                    {{ __('Allow ad on this video') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row flex-column-reverse justify-content-sm-between gap-2 mt-3">
            <a href="{{route('user.videos.index')}}" class="btn btn-secondary">
                {{ __('Cancel') }}
            </a>
            <div class="d-flex flex-column flex-sm-row gap-2">
                <button value="save" type="submit" name="action" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-pen d-none d-sm-block"></i>
                    <span>{{ __('Update & Continue Editing') }}</span>
                </button>
                <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2">
                    <i class="fa-solid fa-pen d-none d-sm-block"></i>
                    <span>{{ __('Update Video') }}</span>
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
            date: initial ? '{{old('scheduled_at', $video->scheduled_at)}}' : '',
            update(e) {
                this.value = e.target.options[e.target.selectedIndex].index == planned_value;
            }
        }));
    })
</script>
