<div class="modal fade" tabindex="-1" id="video_create" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload new video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <video-upload endpoint="{{route('videos.upload')}}"></video-upload>
                <div class="text-sm text-muted mb-3 text-center">
                    Accepted formats : <strong>{{implode(', ', \App\Enums\VideoType::acceptedFormats())}}</strong> - Max file size : <strong>1 GB</strong>
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
