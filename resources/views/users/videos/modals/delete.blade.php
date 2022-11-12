<div class="modal fade" tabindex="-1" id="delete_video" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete this video? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2 p-2 bg-light text-dark d-flex gap-2">
                    <img style="width: 100px" src="http://localhost:8080/storage/posters/c1ty2dcbI73kLWbiUOBk44QUURXw0QLH1zU5RN5k.jpg" alt="">
                    <div>
                        <div>Three Years of Primitive Skills at the Hut (The last video)</div>
                        <small class="text-muted d-block">Publish at 30 october 2022</small>
                        <small class="text-muted">358 views</small>
                    </div>
                </div>
                <p class="alert alert-danger">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Animi aspernatur assumenda consequuntur deserunt dolorem dolores, ipsam laboriosam magnam molestias officia pariatur sapiente tempore voluptates. Ipsa perspiciatis quas quasi soluta voluptates?</p>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a href="" class="btn btn-info text-white">
                    <i class="fa-solid fa-download"></i> &nbsp;
                    Download video
                </a>
                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{route('user.videos.destroy', 1)}}">
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
