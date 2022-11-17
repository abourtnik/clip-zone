<div class="modal fade" tabindex="-1" id="video_likes" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Video interactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">
                            All
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#likes" type="button" role="tab" aria-controls="likes" aria-selected="false">
                            Likes&nbsp;
                            <i class="fa-regular fa-thumbs-up"></i>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#dislikes" type="button" role="tab" aria-controls="dislikes" aria-selected="false">
                            Dislikes&nbsp;
                            <i class="fa-regular fa-thumbs-down"></i>
                        </button>
                    </li>
                </ul>
                <div class="tab-content" style="height: 350px;overflow-y: auto;">
                    <div class="tab-pane active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="d-flex flex-column justify-content-center gap-2 mt-3">
                            <ul class="list-group">
                                @for($i = 0; $i < 20; $i ++)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="#" class="d-flex align-items-center gap-2">
                                            <img class="rounded" src="http://localhost:8080/storage/images/default_men.png" alt="avatar" style="width: 40px;">
                                            <span>Anton Bourtnik</span>
                                        </a>
                                        <button class="btn btn-danger btn-sm">Subscribe</button>
                                    </li>
                                @endfor
                            </ul>
                        </div>
                    </div>
                    <div class="tab-pane" id="likes" role="tabpanel" aria-labelledby="likes-tab">

                    </div>
                    <div class="tab-pane" id="dislikes" role="tabpanel" aria-labelledby="dislikes-tab">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('video_likes').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const elements = button.dataset.elements;

        /*
        for (const [className, attribute] of Object.entries(JSON.parse(elements))) {

            const element = document.querySelector('.video_' + className);
            const value = button.dataset[className];

            if (attribute) {
                element.setAttribute(attribute, value);
            } else {
                element.innerText = value;
            }
        }
        */
    })
</script>
