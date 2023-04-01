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
                    <div class="text-center align-items-center d-flex flex-column w-100 mx-auto">
                        <div class="rounded-circle bg-light-dark p-5 mb-4">
                            <i class="fa-solid fa-upload fa-3x" ></i>
                        </div>
                        <strong class="mb-1">Drag and drop video file to upload</strong>
                        <div class="text-muted">Your video will be private until you publish them.</div>
                        <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0" x-show="error">
                            <span x-text="error"></span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <button class="btn btn-primary mt-4 text-uppercase">
                            Select file
                        </button>
                        <div class="text-sm text-muted mt-3">
                            Accepted formats : <strong>{{implode(', ', \App\Enums\VideoType::acceptedFormats())}}</strong> - Max file size : <strong>200 Mo</strong>
                        </div>
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
            async uploadFile (file) {

                this.error = null;

                document.querySelector('input[name="file"]').value = '';

                if(!file) {
                    return;
                }

                if(!['video/mp4', 'video/webm', 'audio/ogg'].includes(file.type)) {
                    this.error = `The file type is invalid (${file.type}). Allowed types are "video/mp4", "video/webm", "audio/ogg"`;
                    return;
                }

                if(file.size > 209715200) { // 200 mo
                    this.error = `Your file is too large (${Math.round((file.size / 1000000) * 100) / 100} MB) Its size should not exceed 200 MB.`;
                    return;
                }

                this.isUpload = true;

                const duration = await this.getVideoDuration(file);

                this.controller = new AbortController();

                const data = new FormData()
                data.append('file', file)
                data.append('duration', duration)
                fetch('{{route('videos.upload')}}', {
                    method: 'POST',
                    body: data,
                    headers: {
                        "Accept": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    signal: this.controller.signal
                }).then(response => {
                    return response.json()
                }).then(data => {
                    if(data?.message){
                        this.error = data.message;
                    }else {
                        window.location.replace(data.route);
                    }})
                    .catch(error => {
                        document.getElementById('file').value = null;
                        if(error.code !== 20) {
                            this.error = 'Whoops! An error occurred. Please try again later.';
                        }
                    })
                    .finally(() => {
                        this.isUpload = false
                    })
            },
            cancel() {
                this.controller.abort();
            },
            getVideoDuration(file) {
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => {
                        const media = new Audio(reader.result);
                        media.onloadedmetadata = () => resolve(media.duration);
                    };
                    reader.readAsDataURL(file);
                    reader.onerror = error => reject(error);
                });
            }
        }));
    })
</script>
