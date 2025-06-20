<div class="modal fade" tabindex="-1" id="ban_user" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm ban user ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" :action="user.route">
                @csrf
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 text-decoration-none alert alert-secondary">
                        <img class="rounded" :src="user.avatar" :alt="`${user.username} avatar`" style="width: 50px;">
                        <span x-text="user.username"></span>
                    </div>
                    <hr>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="ban_videos">
                        <label class="form-check-label" for="ban_videos">
                            Ban user videos (<span x-text="user.videos"></span>)
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="ban_comments">
                        <label class="form-check-label" for="ban_comments">
                            Ban user comments (<span x-text="user.comments"></span>)
                        </label>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-ban"></i> &nbsp
                        Ban user
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
