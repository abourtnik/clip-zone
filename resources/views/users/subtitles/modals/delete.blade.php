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
                                <h5 id="subtitle_name" class="card-title"></h5>
                                <small id="subtitle_infos" class="text-muted"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="form-delete" method="POST" action="">
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

<script>
    document.getElementById('delete_subtitle').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const name = button.dataset.name;
        const infos = button.dataset.infos;
        const route = button.dataset.route;

        document.getElementById('subtitle_name').innerText = name;
        document.getElementById('subtitle_infos').innerText = infos;
        document.getElementById('form-delete').setAttribute('action', route);
    })
</script>
