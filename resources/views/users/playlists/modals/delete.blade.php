<div class="modal fade" tabindex="-1" id="delete_playlist" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Permanently delete this playlist ?') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 border-dark bg-light-dark">
                    <div class="row g-0">
                        <div class="col-4">
                            <img x-show="playlist.thumbnail" :src="playlist.thumbnail" class="img-fluid rounded-start h-100" :alt="`${playlist.title} thumbnail`" style="object-fit: cover;">
                            <div x-show.important="!playlist.thumbnail" class="bg-secondary text-white d-flex justify-content-center align-items-center w-100 h-100">
                                <i class="fa-solid fa-image fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title" x-text="playlist.title"></h5>
                                <small class="text-muted" x-text="playlist.infos"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <form method="POST" :action="playlist.route">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            {{ __('Delete playlist') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
