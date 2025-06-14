<div class="modal fade" tabindex="-1" id="delete_user" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm delete user ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" :action="user.route">
                @method('DELETE')
                @csrf
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 text-decoration-none alert alert-secondary">
                        <img class="rounded" :src="user.avatar" :alt="`${user.username} avatar`" style="width: 50px;">
                        <span x-text="user.username"></span>
                    </div>
                    <hr>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i> &nbsp;
                        Delete user
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
