<div class="modal fade" tabindex="-1" id="video_likes" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Video interactions') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="interactions-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('video_likes').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.dataset.id;
        document.getElementById('interactions-body').innerHTML = `<interactions-area target=${id} />`;
    })
</script>
