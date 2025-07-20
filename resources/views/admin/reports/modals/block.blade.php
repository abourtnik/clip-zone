<div class="modal fade" tabindex="-1" id="block_report" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Block this content ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 p-2 bg-light-dark text-black">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img style="width: 120px;height: 68px" :src="reportable.poster" :alt="`${reportable.title} poster`">
                        <div>
                            <small  x-text="reportable.title"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" :action="reportable.route">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-ban"></i> &nbsp;
                        Block content
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
