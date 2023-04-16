<div class="modal fade" tabindex="-1" id="comment_replies" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Replies</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('comment_replies').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.dataset.id;
        const video = button.dataset.video;
        document.querySelector('.modal-body').innerHTML = `<replies-area target="${id}" video="${video}" auth="{{auth()->user()?->setAppends(['avatar_url'])->setVisible(['avatar_url', 'username'])}}"/>`;
    })
</script>
