@use('App\Enums\VideoType')

<div class="modal fade" tabindex="-1" id="video_create" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Upload new video') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <video-upload endpoint="{{route('videos.upload')}}" maxSize="{{config('plans.'.Auth::user()->plan.'.max_file_size')}}"></video-upload>
                <div class="text-sm text-muted mb-3 text-center">
                    {{ __('Accepted formats')}} : <strong>{{VideoType::nameToString()}}</strong>
                </div>
                <div class="fw-bold text-center alert alert-info mb-0 radius-end-0 radius-start-0 px-0">
                    <h5 class="alert-heading text-center fw-bold">{{ __('Your upload limits') }}</h5>
                    <hr>
                    @if(!Auth::user()->is_premium)
                        <span @class(['text-danger' => $available_uploads <= 0])>{{ __('Available uploads') }} :
                            <strong> {{ ($available_uploads > 0) ? $available_uploads : 0  }}</strong>
                        </span> •
                    @else
                        <span>{{ __('Available uploads') }} : <strong>{{ __('Unlimited') }}</strong></span> •
                    @endif
                    <span>{{ __('Max file size') }} :
                        <strong>@size(config('plans.'.Auth::user()->plan.'.max_file_size'))</strong>
                    </span> •
                    @if(!Auth::user()->is_premium)
                        <span>{{ __('Available space')}} :
                            <strong>@size(($available_space > 0) ? $available_space : 0)</strong>
                        </span>
                    @else
                        <span>{{ __('Available space')}} : <strong>{{ __('Unlimited')}}</strong></span>
                    @endif
                    @if(!Auth::user()->is_premium)
                        <hr class="w-90 mx-auto">
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
