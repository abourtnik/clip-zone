<div class="modal fade" tabindex="-1" id="share" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex gap-2 align-items-center">
                    <a href="mailto:?body={{$video->route}}" class="rounded-circle p-3 border" style="background-color: #888888;padding: 0.7rem !important;">
                        <i class="fa-regular fa-envelope fa-2x text-white"></i>
                    </a>
                    <a target="_blank"  href="https://www.facebook.com/share.php?u={{$video->route}}" class="rounded-circle p-3 border" style="background-color: #3A5997;padding: 0.7rem !important;">
                        <i class="fa-brands fa-facebook fa-2x text-white"></i>
                    </a>
                    <a target="_blank" href="https://api.whatsapp.com/send/?text={{$video->route}}&type=custom_url&app_absent=0" class="rounded-circle p-3 border" style="background-color: #22D365;padding: 0.7rem !important;">
                        <i class="fa-brands fa-whatsapp fa-2x text-white"></i>
                    </a>
                    <a target="_blank" href="https://twitter.com/share?text={{$video->title}}&url={{$video->route}}" class="rounded-circle p-3 border" style="background-color: #1EA1F1;padding: 0.7rem !important;">
                        <i class="fa-brands fa-twitter fa-2x text-white"></i>
                    </a>
                    <a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{$video->route}}" class="rounded-circle p-3 border" style="background-color: #0277B5;padding: 0.7rem !important;">
                        <i class="fa-brands fa-linkedin fa-2x text-white"></i>
                    </a>
                    <a target="_blank" href="" class="rounded-circle p-3 border" style="background-color: #4680C2;padding: 0.7rem !important;">
                        <i class="fa-brands fa-vk fa-2x text-white"></i>
                    </a>
                    <a target="_blank" href="https://www.pinterest.fr/pin/create/button/?description=test&url={{$video->route}}" class="rounded-circle p-3 border" style="background-color: #BC091C;padding: 0.7rem !important;">
                        <i class="fa-brands fa-pinterest fa-2x text-white"></i>
                    </a>
                </div>
                <hr>
                <label for="link" class="form-label">Video Link</label>
                <div class="mb-3 input-group">
                    <input type="text" class="form-control" readonly id="link" value="{{$video->route}}">
                    <button
                        class="btn btn-primary"
                        type="button"
                        x-data
                        @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                        title="Copy video link"
                        data-link="{{$video->route}}"
                        data-bs-toggle="tooltip"
                        data-bs-title="Link copied !"
                        data-bs-trigger="click"
                    >
                        Copy
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
