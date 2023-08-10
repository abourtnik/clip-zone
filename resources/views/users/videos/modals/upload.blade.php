<div class="modal fade" tabindex="-1" id="video_create" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload new video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <video-upload endpoint="{{route('videos.upload')}}" maxSize="{{config('plans.'.Auth::user()->plan.'.max_file_size')}}"></video-upload>
                <div class="text-sm text-muted mb-3 text-center">
                    Accepted formats : <strong>{{implode(', ', \App\Enums\VideoType::acceptedFormats())}}</strong>
                </div>
                <div class="fw-bold text-center alert alert-info mb-0 radius-end-0 radius-start-0 px-0">
                    <h5 class="alert-heading text-center fw-bold">Your upload limits</h5>
                    <hr>
                    @if(!Auth::user()->is_premium)
                        <span>Available uploads : <strong>{{config('plans.free.max_uploads') - Auth::user()->videos()->count()}}</strong></span> •
                    @else
                        <span>Available uploads : <strong>Unlimited</strong></span> •
                    @endif
                    <span>Max file size : <strong>@size(config('plans.'.Auth::user()->plan.'.max_file_size'))</strong></span> •
                    @if(!Auth::user()->is_premium)
                        <span>Available space : <strong>@size(config('plans.'.Auth::user()->plan.'.max_videos_storage') - $user_space)</strong></span>
                    @else
                        <span>Available space : <strong>Unlimited</strong></span>
                    @endif
                    @if(!Auth::user()->is_premium)
                        <hr class="w-90 mx-auto">
                        <a href="{{route('pages.premium')}}" class="alert-link">Upgrade to premium for unlimited uploads</a>
                    @endif
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="text-muted text-sm text-center">
                    <p class="mb-1">
                        By submitting your videos to {{config('app.name')}}, you acknowledge that you agree to our <a href="{{route('pages.terms')}}" class="text-decoration-none">Terms of Service</a>.
                    </p>
                    <p>
                        Please be sure not to violate others copyright or privacy rights.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
