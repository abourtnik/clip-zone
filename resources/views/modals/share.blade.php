<div class="modal fade" tabindex="-1" id="share" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share Video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="mb-3">Share</h5>
                <div class="d-flex gap-2 align-items-center">
                    <a href="mailto:?body=Bonjour" class="rounded-circle p-3 border" style="background-color: #888888;padding: 0.7rem !important;">
                        <i class="fa-regular fa-envelope fa-2x text-white"></i>
                    </a>
                    <a href="https://www.facebook.com/share.php?u=hubspot.com" class="rounded-circle p-3 border" style="background-color: #3A5997;padding: 0.7rem !important;">
                        <i class="fa-brands fa-facebook fa-2x text-white"></i>
                    </a>
                    <a target="_blank" href="https://api.whatsapp.com/send/?text=bonjour&type=custom_url&app_absent=0" class="rounded-circle p-3 border" style="background-color: #22D365;padding: 0.7rem !important;">
                        <i class="fa-brands fa-whatsapp fa-2x text-white"></i>
                    </a>
                    <a target="_blank" href="https://twitter.com/share?text=text goes here&url=http://url goes here&hashtags=hashtag1,hashtag2,hashtag3" class="rounded-circle p-3 border" style="background-color: #1EA1F1;padding: 0.7rem !important;">
                        <i class="fa-brands fa-twitter fa-2x text-white"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=https://www.antonbourtnik.fr" class="rounded-circle p-3 border" style="background-color: #0277B5;padding: 0.7rem !important;">
                        <i class="fa-brands fa-linkedin fa-2x text-white"></i>
                    </a>
                    <a href="" class="rounded-circle p-3 border" style="background-color: #4680C2;padding: 0.7rem !important;">
                        <i class="fa-brands fa-vk fa-2x text-white"></i>
                    </a>
                    <a href="https://www.pinterest.fr/pin/create/button/?description=test&url=https://www.antonbourtnik.fr/" class="rounded-circle p-3 border" style="background-color: #BC091C;padding: 0.7rem !important;">
                        <i class="fa-brands fa-pinterest fa-2x text-white"></i>
                    </a>
                </div>
                <hr>
                <label for="link" class="form-label">Video Link</label>
                <div class="mb-3 input-group">
                    <input type="text" class="form-control" readonly id="link" value="http://localhost:8080/video/15c0141a-1028-3dba-a3d3-08b489e733c0">
                    <button
                        class="btn btn-primary"
                        type="button"
                        x-data
                        @click="navigator.clipboard.writeText($event.currentTarget.dataset.link)"
                        title="Copy video link"
                        data-link="http://localhost:8080/video/15c0141a-1028-3dba-a3d3-08b489e733c0"
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


<script>
    document.getElementById('share').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const url = button.dataset.url;

        //document.querySelector('input[name="id"]').value = id;
    })
</script>
