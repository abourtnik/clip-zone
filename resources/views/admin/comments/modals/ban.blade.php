<div class="modal fade" tabindex="-1" id="ban_comment" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm ban comment ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-2 alert alert-secondary align-items-start">
                    <img id="avatar" class="rounded" src="" alt="avatar" style="width: 50px;">
                    <div>
                        <div class="d-flex align-items-center gap-1">
                            <div id="username" class="text-sm text-decoration-none"></div>
                            <span>•</span>
                            <small id="date" class="text-muted"></small>
                        </div>
                        <div class="text-muted text-sm" id="content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <div class="d-flex gap-1">
                    <form id="form-ban" method="POST" action="">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-ban"></i> &nbsp;
                            Ban comment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('ban_comment').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const route = button.dataset.route;
        const username = button.dataset.username;
        const avatar = button.dataset.avatar;
        const date = button.dataset.date;
        const content = button.dataset.content;

        document.getElementById('form-ban').setAttribute('action', route);
        document.getElementById('username').innerText = username;
        document.getElementById('avatar').setAttribute('src', avatar);
        document.getElementById('date').innerText = date;
        document.getElementById('content').innerText = content;
    })
</script>
