<div class="modal fade" tabindex="-1" id="video_create" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload new video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div x-data="upload">
                    <div x-show.important="!isUpload || error" class="text-center align-items-center d-flex flex-column w-50 mx-auto">
                        <i class="fa-solid fa-upload fa-3x rounded-circle bg-light-dark p-5 mb-4"></i>
                        <strong class="mb-1">Drag and drop video file to upload</strong>
                        <div class="text-muted">Your video will be private until you publish them.</div>
                        <div class="text-danger fw-bold mt-3" x-show="error">
                            <span x-text="error"></span>
                        </div>
                        <label class="btn btn-primary mt-4 text-uppercase">
                            Select file <input type="file" class="d-none" @change="uploadFile($event.target.files[0])">
                        </label>
                    </div>
                    <div x-show.important="isUpload && !error" class="text-center align-items-center d-flex flex-column w-50 mx-auto">
                        <img  src="/images/wait.gif" alt="" style="width: 145px;" class="rounded-circle bg-light-dark p-4 mb-4">
                        <strong class="mb-1">Your video is uploading ... </strong>
                        <div class="text-primary fw-bold">Dont quit or reload page please</div>
                        <label class="btn btn-secondary mt-4 text-uppercase">
                            Cancel Upload
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="text-muted text-sm text-center">
                    <p class="mb-1">
                        By submitting your videos to {{config('app.name')}}, you acknowledge that you agree to our <a href="{{route('pages.terms')}}" class="text-decoration-none">Terms of Service</a>.
                    </p>
                    <p>
                        Please be sure not to violate others' copyright or privacy rights.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('upload', () => ({
            isUpload: false,
            error: null,
            uploadFile(file) {
                this.isUpload = true;
                this.error = null;
                const data = new FormData()
                data.append('file', file)
                fetch('{{route('videos.upload')}}', {
                    method: 'POST',
                    body: data,
                    headers: {
                        "Accept": "application/json",
                    },
                }).then(response => {
                    return response.json()
                }).then(data => {
                    console.log('here')
                    console.log(data)
                    if(data?.message){
                        console.log(data.message)
                        this.error = data.message;
                    }else {
                        window.location.replace(data.route);
                    }})
                    .catch(error => {
                        console.log('error')
                        console.error(error)
                        this.error = 'An error has occurred';
                    })
                    .finally(() => {
                        this.isUpload = false
                    })
            }
        }));
    })
</script>
