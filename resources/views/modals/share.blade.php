<div class="modal fade" tabindex="-1" id="share" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img class="img-fluid rounded img-thumbnail mb-3" src="{{$video->thumbnail_url}}" alt="{{$video->title}} Thumbnail">
                <div class="d-flex flex-wrap" style="row-gap: 1rem !important;">
                    <a href="mailto:?body={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                        <i class="fa fa-circle fa-stack-2x" style="color: #888888;"></i>
                        <i class="fa-regular fa-envelope fa-stack-1x fa-inverse"></i>
                    </a>
                    <a target="_blank" href="https://www.facebook.com/share.php?u={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                        <i class="fa fa-circle fa-stack-2x" style="color: #3A5997;"></i>
                        <i class="fa-brands fa-facebook fa-stack-1x fa-inverse"></i>
                    </a>
                    <a target="_blank" href="https://api.whatsapp.com/send/?text={{$video->route}}&type=custom_url&app_absent=0" class="fa-stack fa-2x" style="font-size: 1.6em">
                        <i class="fa fa-circle fa-stack-2x" style="color: #22D365;"></i>
                        <i class="fa-brands fa-whatsapp fa-stack-1x fa-inverse"></i>
                    </a>
                    <a target="_blank" href="https://twitter.com/share?text={{$video->title}}&url={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                        <i class="fa fa-circle fa-stack-2x" style="color: #1EA1F1;"></i>
                        <i class="fa-brands fa-twitter fa-stack-1x fa-inverse"></i>
                    </a>
                    <a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                        <i class="fa fa-circle fa-stack-2x" style="color: #0277B5;"></i>
                        <i class="fa-brands fa-linkedin fa-stack-1x fa-inverse"></i>
                    </a>
                    <a target="_blank" href="" class="fa-stack fa-2x" style="font-size: 1.6em">
                        <i class="fa fa-circle fa-stack-2x" style="color: #4680C2;"></i>
                        <i class="fa-brands fa-vk fa-stack-1x fa-inverse"></i>
                    </a>
                    <a target="_blank" href="https://www.pinterest.fr/pin/create/button/?description=test&url={{$video->route}}" class="fa-stack fa-2x" style="font-size: 1.6em">
                        <i class="fa fa-circle fa-stack-2x" style="color: #BC091C;"></i>
                        <i class="fa-brands fa-pinterest fa-stack-1x fa-inverse"></i>
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
