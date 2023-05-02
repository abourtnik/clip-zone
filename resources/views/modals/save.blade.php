<div class="modal fade" tabindex="-1" id="save" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Save Video to ...</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="modal-body"></div>
        </div>
    </div>
</div>

<script>
    document.getElementById('save').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.dataset.id;
        document.getElementById('modal-body').innerHTML = `<save-video video=${id} />`;
    })
</script>
