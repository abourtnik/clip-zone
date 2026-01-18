<div class="modal fade" tabindex="-1" id="comment_delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Permanently delete comment ?') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    Delete <i class="fw-bold" x-text="comment.author"></i> comment and <strong class="fw-bold" x-text="comment.repliesCount"></strong> associated replies
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                <div class="d-flex gap-1">
                    <form method="POST" :action="comment.route">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            {{ __('Delete comment') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
