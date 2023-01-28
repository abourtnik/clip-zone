<div class="modal fade" tabindex="-1" id="comment_delete" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete comment ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    Delete <i class="fw-bold" id="author"></i> comment and <strong class="fw-bold" id="replies_count"></strong> associated replies
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <div class="d-flex gap-1">
                    <form id="form-delete" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            Delete comment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('comment_delete').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const route = button.dataset.route;
        const replies_count = button.dataset.repliesCount;
        const author = button.dataset.author;

        document.getElementById('form-delete').setAttribute('action', route);
        document.getElementById('replies_count').innerText = replies_count;
        document.getElementById('author').innerText = author;
    })
</script>
