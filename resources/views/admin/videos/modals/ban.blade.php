<div class="modal fade" tabindex="-1" id="ban_video" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm ban video ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 p-2 bg-light-dark text-dark">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <img id="video_image" style="width: 120px;height: 68px" src="" alt="" class="video_poster video_alt">
                        <div>
                            <small id="video_title"></small>
                            <div id="video_infos" class="text-muted video_infos text-sm mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="video_route" method="POST" action="">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-ban"></i> &nbsp;
                        Ban video
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('ban_video').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const title = button.dataset.title;
        const infos = button.dataset.infos;
        const poster = button.dataset.poster;
        const route = button.dataset.route;

        document.getElementById('video_title').innerText = title;
        document.getElementById('video_infos').innerText = infos;
        document.getElementById('video_image').setAttribute('src', poster);
        document.getElementById('video_route').setAttribute('action', route);
    })
</script>
