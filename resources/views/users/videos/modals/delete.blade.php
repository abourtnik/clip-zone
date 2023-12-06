<div class="modal fade" tabindex="-1" id="delete_video" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete this video? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card mb-3 border-dark bg-light-dark">
                    <div class="row g-0">
                        <div class="col-4">
                            <img src="" class="img-fluid rounded-start h-100 video_poster video_alt" alt="" style="object-fit: cover;">
                            <div id="default-thumbnail" class="bg-secondary text-white d-flex justify-content-center align-items-center d-none w-100 h-100">
                                <i class="fa-solid fa-image fa-2x"></i>
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="card-body">
                                <h5 class="card-title video_title"></h5>
                                <small class="text-muted video_infos"></small>
                            </div>
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
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            Delete video
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('delete_video').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const elements = button.dataset.elements;

        for (const [className, attribute] of Object.entries(JSON.parse(elements))) {

            const element = document.querySelector('.video_' + className);
            const value = button.dataset[className];

            if(className === 'poster' && !value) {
                element.classList.add('d-none')
                document.getElementById('default-thumbnail').classList.add('d-block')
                document.getElementById('default-thumbnail').classList.remove('d-none')
            }

            if (attribute) {
                element.setAttribute(attribute, value);
            } else {
                element.innerText = value;
            }
        }
    })
</script>
