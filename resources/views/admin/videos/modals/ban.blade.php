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
                        <img style="width: 120px;height: 68px" src="" alt="" class="video_poster video_alt">
                        <div>
                            <small class="video_title"></small>
                            <div class="text-muted video_infos text-sm mt-1"></div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-danger">
                    <p class="fw-bold">Deleting video is permanent and can't be undone</p>
                    <p>The deletion of a video automatically leads to the deletion of comments as well as related interactions.</p>
                    <ul>
                        <li>
                            <span class="video_comments"></span>
                        </li>
                        <li>
                            <span class="video_likes"></span>
                        </li>
                        <li>
                            <span class="video_dislikes"></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="" class="btn btn-info text-white video_download">
                    <i class="fa-solid fa-download"></i> &nbsp;
                    Download video
                </a>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="" class="video_route">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            Ban video
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('ban_video').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const elements = button.dataset.elements;

        for (const [className, attribute] of Object.entries(JSON.parse(elements))) {

            const element = document.querySelector('.video_' + className);
            const value = button.dataset[className];

            if (attribute) {
                element.setAttribute(attribute, value);
            } else {
                element.innerText = value;
            }
        }
    })
</script>