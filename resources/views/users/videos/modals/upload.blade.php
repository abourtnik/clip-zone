@use('App\Enums\VideoType')

<div class="modal fade" tabindex="-1" id="video_create" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-fullscreen-lg-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Upload new video') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <video-upload endpoint="{{route('videos.upload')}}" maxSize="{{config('plans.'.Auth::user()->plan.'.max_file_size')}}"></video-upload>
                <div class="text-sm text-muted mb-3 text-center px-2">
                    {{ __('Accepted formats')}} : <strong>{{VideoType::nameToString()}}</strong>
                </div>
                <div class="fw-bold text-center alert alert-info mb-0 px-0 tw:rounded-none!">
                    <h5 class="alert-heading text-center fw-bold">{{ __('Your upload limits') }}</h5>
                    <hr>
                    <div class="flex flex-column flex-md-row justify-content-center gap-1 align-content-center px-2">
                        <div @class(['d-flex align-content-center gap-1 justify-content-center', 'text-danger' => !Auth::user()->is_premium && $available_uploads == 0])>
                            <span>{{ __('Available uploads') }} :</span>
                            @if(Auth::user()->is_premium)
                                <strong>{{ __('Unlimited') }}</strong>
                            @else
                                <strong>{{ $available_uploads }}</strong>
                            @endif
                        </div>
                        <span class="d-none d-md-inline-block">•</span>
                        <div class="d-flex align-content-center gap-1 justify-content-center">
                            <span>{{ __('Max file size') }} :</span>
                            <strong>@size(config('plans.'.Auth::user()->plan.'.max_file_size'))</strong>
                        </div>
                        <span class="d-none d-md-inline-block">•</span>
                        <div class="d-flex align-content-center gap-1 justify-content-center">
                            <span>{{ __('Available space')}} :</span>
                            @if(Auth::user()->is_premium)
                                <strong>{{ __('Unlimited')}}</strong>
                            @else
                                <strong>@size($available_space, 1)</strong>
                            @endif
                        </div>
                    </div>
                    @if(!Auth::user()->is_premium)
                        <hr class="w-100">
                        <a href="{{route('pages.premium')}}" class="alert-link">{{ __('Upgrade to premium for unlimited uploads') }}</a>
                    @endif
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="text-muted text-sm text-center">
                    <p class="mb-1">
                        {!! __('By submitting your videos to :app_name, you acknowledge that you agree to our <a href=":terms_url" class="text-decoration-none">Terms of Service</a>', ['app_name' => config('app.name'), 'terms_url' => route('pages.terms')]) !!}
                    </p>
                    <p>
                        {{ __('Please be sure not to violate others copyright or privacy rights') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
