<div class="modal fade" tabindex="-1" id="delete_video" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Permanently delete this video ?') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 border-dark bg-light-dark">
                    <div class="row g-0">
                        <div class="col-4">
                            <img x-show="video.poster" :src="video.poster" class="img-fluid rounded-start h-100" :alt="`${video.title} thumbnail`" style="object-fit: cover;">
                            <div x-show.important="!video.poster" class="bg-secondary text-white d-flex justify-content-center align-items-center w-100 h-100">
                                <i class="fa-solid fa-image fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title video_title" x-text="video.title"></h5>
                                <small class="text-muted video_infos" x-text="video.infos"></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger">
                    <p class="fw-bold">{{ __('Deleting video is permanent and can\'t be undone') }}</p>
                    <p>{{ __('The deletion of a video automatically leads to the deletion of comments as well as related interactions') }}</p>
                    <ul>
                        <li x-text="video.comments"></li>
                        <li x-text="video.likes"></li>
                        <li x-text="video.dislikes"></li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer d-flex" :class="{ 'justify-content-between': video.canDownload, 'justify-content-end': !video.canDownload }">
                <a :download="video.title" :href="video.download" class="btn btn-info text-white" x-show="video.canDownload">
                    <i class="fa-solid fa-download"></i> &nbsp;
                    {{ __('Download video') }}
                </a>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form method="POST" :action="video.route">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            {{ __('Delete video') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
