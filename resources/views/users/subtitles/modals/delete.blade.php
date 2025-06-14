<div class="modal fade" tabindex="-1" id="delete_subtitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete this subtitle ?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 border-dark bg-light-dark">
                    <div class="row g-0">
                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title" x-text="subtitle.name"></h5>
                                <small class="text-muted" x-text="subtitle.infos"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form  method="POST" :action="subtitle.route">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            Delete subtitle
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
