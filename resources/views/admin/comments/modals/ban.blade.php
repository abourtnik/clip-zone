<div class="modal fade" tabindex="-1" id="ban_comment" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Confirm ban comment ?') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-3 alert alert-secondary align-items-start">
                    <img class="rounded" :src="comment.userAvatar" :alt="`${comment.userUsername} avatar`" style="width: 50px;">
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-1">
                            <div class="text-sm text-decoration-none" x-text="comment.userUsername"></div>
                            <span>•</span>
                            <small class="text-muted" x-text="comment.date"></small>
                        </div>
                        <div class="text-muted text-sm text-break" x-text="comment.content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <div class="d-flex gap-1">
                    <form method="POST" :action="comment.route">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-ban"></i> &nbsp;
                            {{ __('Ban comment') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
