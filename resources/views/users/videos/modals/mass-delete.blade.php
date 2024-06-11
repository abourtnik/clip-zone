<div class="modal fade" tabindex="-1" id="mass_delete" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete  <span x-text="selected.length"></span> <span x-text="selected.length === 1 ? 'video' : 'videos'"></span> ?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <p class="fw-bold">Deleting videos is permanent and can't be undone</p>
                    <p>The deletion of a video automatically leads to the deletion of comments as well as related interactions.</p>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button @click="$refs.deleteForm.submit()" class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> &nbsp;
                    Delete <span x-text="selected.length"></span> <span x-text="selected.length === 1 ? 'video' : 'videos'"></span>
                </button>
            </div>
        </div>
    </div>
</div>
