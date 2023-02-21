<div class="modal fade" tabindex="-1" id="delete_playlist" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete this playlist ?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="form-delete" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            Delete playlist
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('delete_playlist').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const route = button.dataset.route;

        document.getElementById('form-delete').setAttribute('action', route);
    })
</script>
