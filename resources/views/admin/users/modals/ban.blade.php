<div class="modal fade" tabindex="-1" id="ban_user" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm ban user ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-ban" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 text-decoration-none alert alert-secondary">
                        <img id="avatar" class="rounded" src="" alt="" style="width: 50px;">
                        <span id="username"></span>
                    </div>
                    <hr>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="ban_videos" id="ban_videos">
                        <label class="form-check-label" for="ban_videos">
                            Ban user videos (<span id="videos"></span>)
                        </label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="ban_comments" id="ban_comments">
                        <label class="form-check-label" for="ban_comments">
                            Ban user comments (<span id="comments"></span>)
                        </label>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-ban"></i> &nbsp;
                        Ban user
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('ban_user').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const route = button.dataset.route;
        const username = button.dataset.username;
        const avatar = button.dataset.avatar;
        const videos = button.dataset.videos;
        const comments = button.dataset.comments;

        document.getElementById('form-ban').setAttribute('action', route);
        document.getElementById('username').innerText = username;
        document.getElementById('avatar').setAttribute('src', avatar);
        document.getElementById('videos').innerText = videos;
        document.getElementById('comments').innerText = comments;
    })
</script>
