<h5 class="text-primary mt-3">{{ __('Subtitles') }}</h5>
<hr>
<div class="alert alert-info text-sm">
    <p>
        {{ __('A subtitle or closed caption file contains spoken dialogue with timestamps indicating when text appears on screen. Some files also include positioning and styling for accessibility')}}
    </p>
    <p class="fw-bold mb-0">
        {{ __('Currently, only WebVTT format is supported')}}
    </p>
</div>
<div x-data="{subtitles: {{$subtitles}}}">
    <template x-for="(subtitle, index) in subtitles" :key="index">
        <div class="my-3 bg-surface-light border border-1 px-2 py-3">
            <div x-data="{language: '' }" class="row align-items-start row-gap-3 gx-2 row-gap-xxl-0">
                <template x-if="subtitle.id">
                    <input type="hidden" :name="'subtitles[' + index + '][id]'" required :value="subtitle.id">
                </template>
                <div class="col-12 col-sm-6 col-xxl-3 mt-0">
                    <select
                        class="form-control form-control-sm"
                        :name="'subtitles[' + index + '][language]'"
                        :id="'subtitles[' + index + '][language]'"
                        @change="language = $event.target.options[$event.target.selectedIndex].text;"
                        required
                        x-model="subtitle.language"
                    >
                        <option value="" selected>--- {{ __('Select Language')}} ---</option>
                        @foreach($languages as $code => $language)
                            <option @selected(old('language') == $code) value="{{$code}}">{{ucfirst($language)}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-xxl-3 mt-0">
                    <div class="input-group input-group-sm">
                        <input
                            type="text"
                            class="form-control form-control-sm"
                            :id="'subtitles[' + index + '][name]'"
                            :name="'subtitles[' + index + '][name]'"
                            placeholder="{{ __('Subtitle name')}}"
                            required
                            :value="subtitle.name ?? language"
                            maxlength="{{config('validation.subtitle.name.max')}}"
                            x-ref="name"
                        >
                        <button
                            type="button"
                            class="btn btn-outline-secondary border border-1"
                            data-bs-toggle="tooltip"
                            data-bs-title="{{ __('Name which will be displayed on the video player')}}"
                        >
                            <i class="fa-solid fa-info-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xxl-3 mt-0">
                    <select
                        class="form-control form-control-sm"
                        :name="'subtitles[' + index + '][status]'"
                        :id="'subtitles[' + index + '][status]'"
                        required
                        x-model="subtitle.status"
                    >
                        <option value="" selected>--- {{ __('Select Status')}} ---</option>
                        @foreach($subtitlesStatus as $id => $name)
                            <option value="{{$id}}">{{$name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-xxl-3 mt-0">
                    <div class="d-flex align-items-start">
                        <div class="d-flex flex-column gap-1 w-100 text-start">
                            <label class="border border-1 cursor-pointer rounded w-100 relative bg-white" x-data="{file:null}" style="padding: 0.25rem 0.5rem;">
                                <input :required="!subtitle?.file" type="file" :name="'subtitles[' + index + '][file]'" @change="file = $event.target.files[0]" accept="text/vtt" class="position-absolute opacity-0 cursor-pointer inset" />
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="fa fa-upload text-sm"></i>
                                    <small x-show="subtitle?.file && !file">{{ __('Update subtitles')}}</small>
                                    <small x-show="!subtitle?.file && !file">{{ __('Upload subtitles')}}</small>
                                    <small x-show="file" x-text="file?.name"></small>
                                </div>
                            </label>
                            <a class="text-sm text-decoration-none" x-show="subtitle?.file" :href="subtitle?.file_url" :download="subtitle.name + '.vtt'">{{ __('Download subtitles')}}</a>
                        </div>
                        <button class="text-danger bg-transparent d-none d-xxl-block" type="button" @click="subtitles.splice(subtitles.indexOf(subtitle), 1)" style="padding: 0.375rem 0.75rem;width: 20px">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button class="btn btn-danger btn-sm d-flex d-xxl-none mt-3" type="button" @click="subtitles.splice(subtitles.indexOf(subtitle), 1)">
                <span>{{ __('Delete')}}</span>
            </button>
        </div>
    </template>
    <button class="btn btn-success btn-sm d-flex align-items-center gap-1" @click="subtitles.push({})" type="button">
        <i class="fa-solid fa-plus"></i>
        <span>{{ __('Add subtitles')}}</span>
    </button>
</div>
