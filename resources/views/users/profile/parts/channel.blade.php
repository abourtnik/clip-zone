<div class="row align-items-start my-3">
    <div class="col-12 col-xl-4">
        <h2>{{ __('Channel Informations') }}</h2>
        <p class="text-muted">{{ __('Update you channel informations') }}</p>
    </div>
    <div class="col-12 col-xl-8">
        <div class="card shadow-soft my-3">
            <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @method('PUT')
                    @csrf
                    <div
                        class="position-relative overflow-hidden"
                        x-data="{hover:false}"
                        @mouseover="hover=true"
                        @mouseleave="hover=false"
                        @cropped-banner.window="$refs.image.setAttribute('src', URL.createObjectURL($event.detail))"
                    >
                        <img x-ref="image" class="img-fluid w-100" src="{{$user->banner_url}}" alt="Banner" >
                        <div
                            class="position-absolute w-100 text-center text-white start-50 z-2"
                            :class="hover ? 'opacity-100 top-50' : 'opacity-0 top-75'"
                            style="transition: all 0.3s ease-in-out 0s;transform: translate(-50%, -50%);"
                        >
                            <div class="fw-bold">{{ __('Click to update Banner') }}</div>
                        </div>
                        <image-upload
                            name="banner"
                            config="banner"
                            class="position-absolute bottom-0 left-0 right-0 top-0 opacity-0 cursor-pointer w-100 z-3"
                        >
                        </image-upload>
                        <div
                            style="transition: all 0.4s ease-in-out 0s;"
                            class="position-absolute bg-dark bg-opacity-75 bottom-0 left-0 right-0 top-0 w-100"
                            :class="hover ? 'opacity-100' : 'opacity-0'"
                        >
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="website" class="form-label">{{ __('Website') }}</label>
                            <input
                                class="form-control"
                                type="text"
                                id="website"
                                name="website"
                                value="{{old('website', $user->website)}}"
                                maxlength="{{config('validation.user.website.max')}}"
                            >
                        </div>
                    </div>
                    <div class="row" x-data="{ count: 0 }" x-init="count = $refs.message.value.length">
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">{{ __('Channel Description') }}</label>
                            <textarea
                                class="form-control"
                                id="description"
                                rows="6"
                                name="description"
                                maxlength="{{config('validation.user.description.max')}}"
                                x-ref="message"
                                @keyup="count = $refs.message.value.length"
                            >{{old('description', $user->description)}}</textarea>
                            <div class="form-text">
                                <span x-text="count"></span> / <span>{{config('validation.user.description.max')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="form-check form-switch">
                                <input type="hidden" value="0" name="show_subscribers">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="show_subscribers"
                                    value="1"
                                    name="show_subscribers"
                                    @checked(old('show_subscribers', $user->show_subscribers))
                                >
                                <label class="form-check-label" for="show_subscribers">
                                    {{ __('Show my subscribers count') }}
                                </label>
                            </div>
                            <div class="form-text">
                                {!! __('By disabling this option, you don\'t appear on <a class="text-decoration-none" href=":discover_url">discover</a> page', ['discover_url' => route('subscription.discover')]) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex flex-column flex-sm-row gap-2 justify-content-between">
                    <a class="btn btn-success d-flex align-items-center gap-2" href="{{$user->route}}">
                        <i class="fa-solid fa-eye"></i>
                        <span>{{ __('Show my channel') }}</span>
                    </a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="fa-solid fa-user-edit"></i>
                        <span>{{ __('Update My Channel') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
