<div class="modal fade" tabindex="-1" id="delete_video" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete this video? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 p-2 bg-light-dark text-dark d-flex gap-2">
                    <img style="width: 100px" src="" alt="" class="video_poster video_alt">
                    <div>
                        <div class="video_title"></div>
                        <small class="text-muted d-block video_publication"></small>
                        <small class="text-muted video_views"></small>
                    </div>
                </div>
                <div class="alert alert-danger">
                    <p class="fw-bold">La suppresion d'une video est definitive.</p>
                    <p>La suppresion d'une video entraîne automatiquement la suppresion des commentaires ainsi que des interactions liees.</p>
                    <ul>
                        <li><span class="video_comments"></span> comments</li>
                        <li><span class="video_likes"></span> likes</li>
                        <li><span class="video_dislikes"></span> dislikes</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="" class="btn btn-info text-white">
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

            if (attribute) {
                element.setAttribute(attribute, value);
            } else {
                element.innerText = value;
            }
        }
    })
</script>
