<div class="modal fade" tabindex="-1" id="delete_device" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="form-delete" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Delete device ? </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-0 fw-bold">
                        The device will no longer be connected to this account
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('delete_device').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const route = button.dataset.route;

        document.getElementById('form-delete').setAttribute('action', route);
    })
</script>
