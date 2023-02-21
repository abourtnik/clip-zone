<div class="modal fade" tabindex="-1" id="cancel-report" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm cancel report ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form id="form-cancel" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-ban"></i> &nbsp;
                        Cancel report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('cancel-report').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const route = button.dataset.route;

        document.getElementById('form-cancel').setAttribute('action', route);
    })
</script>
