<div class="modal fade" tabindex="-1" id="video_create" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload new video</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0" x-data="upload">
                <div x-show.important="!isUpload || error" class="block w-full position-relative bg-white appearance-none border-2 border-gray-300 border-solid rounded-md hover:shadow-outline-gray p-3">
                    <label class="d-none" for="file"></label>
                    <input
                        id="file"
                        type="file"
                        name="file"
                        class="position-absolute top-0 bottom-0 start-0 end-0 w-100 h-100 outline-none opacity-0"
                        style="cursor: pointer"
                        @drop="uploadFile($event.target.files[0])"
                        @change="uploadFile($event.target.files[0])"
                    >
                    <div class="text-center align-items-center d-flex flex-column w-50 mx-auto">
                        <div class="rounded-circle bg-light-dark p-5 mb-4">
                            <i class="fa-solid fa-upload fa-3x" ></i>
                        </div>
                        <strong class="mb-1">Drag and drop video file to upload</strong>
                        <div class="text-muted">Your video will be private until you publish them.</div>
                        <div class="text-danger fw-bold mt-3" x-show="error">
                            <span x-text="error"></span>
                        </div>
                        <button class="btn btn-primary mt-4 text-uppercase">
                            Select file
                        </button>
                    </div>
                </div>
                <div x-show.important="isUpload && !error" class="text-center align-items-center d-flex flex-column w-50 mx-auto p-3">
                    <div class="rounded-circle bg-light-dark p-4 mb-4">
                        <i class="fas fa-spinner fa-spin fa-3x"></i>
                    </div>
                    <strong class="mb-1">Your video is uploading ... </strong>
                    <div class="text-primary fw-bold">Dont quit or reload page please</div>
                    <button class="btn btn-secondary mt-4 text-uppercase" @click="cancel()">
                        Cancel Upload
                    </button>
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="text-muted text-sm text-center">
                    <p class="mb-1">
                        By submitting your videos to {{config('app.name')}}, you acknowledge that you agree to our <a href="{{route('pages.terms')}}" class="text-decoration-none">Terms of Service</a>.
                    </p>
                    <p>
                        Please be sure not to violate others copyright or privacy rights.
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
            controller: null,
            uploadFile(file) {
                if(!file) {
                    return;
                }
                this.controller = new AbortController();
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
                    signal: this.controller.signal
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
                        document.getElementById('file').value = null;
                        if(error.code !== 20) {
                            console.log('error')
                            console.error(error)
                            console.log(error.code)
                            this.error = 'An error has occurred';
                        }
                    })
                    .finally(() => {
                        this.isUpload = false
                    })
            },
            cancel() {
                this.controller.abort();
            }
        }));
    })
</script>
