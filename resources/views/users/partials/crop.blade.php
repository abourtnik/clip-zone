<div class="modal modal-lg fade" tabindex="-1" id="crop" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customize picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img style="display: block;max-width: 100%;" id="cropped_image" src="" alt="cropped image">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="crop_button" type="button" class="btn btn-primary" data-bs-dismiss="modal">Done</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('crop').addEventListener('shown.bs.modal', event => {

        const croppedImage = document.getElementById('cropped_image');
        const name = event.relatedTarget.dataset.name;
        const input = document.querySelector(`input[name="${name}"]`);

        const width = event.relatedTarget.dataset.width;
        const height = event.relatedTarget.dataset.height;
        const cropArea = event.relatedTarget.dataset.area;
        const aspectRatio = event.relatedTarget.dataset.ratio ?? null;
        const resizeable = event.relatedTarget.dataset.resizeable;

        window.cropper = new Cropper(croppedImage, {
            aspectRatio: aspectRatio,
            minCropBoxWidth: width,
            minCropBoxHeight: height,
            viewMode: 2,
            modal: true,
            background: true,
            center: true,
            autoCrop: true,
            autoCropArea: cropArea,
            dragMode: 'move',
            responsive: true,
            cropBoxResizable: resizeable,
            guides : false,
            ready : () => {
                if(event.relatedTarget.dataset.name === 'avatar') {
                    document.querySelector('.cropper-view-box').style.borderRadius = '50%';
                    document.querySelector('.cropper-face').style.borderRadius = '50%';
                }
            }
        });

        document.getElementById('crop_button').addEventListener('click', e => {
            const canvas = window.cropper.getCroppedCanvas({
                minWidth: width,
                minHeight: height,
            });

            document.querySelector(`image-upload[name="${name}"]`).setAttribute('source', canvas.toDataURL());

            canvas.toBlob(function (blob) {
                let file = new File([blob], "img.jpg",{type:"image/jpeg", lastModified:new Date().getTime()});
                let container = new DataTransfer();

                container.items.add(file);
                input.files = container.files;
            });
        }, {
            once: true
        })
    })

    document.getElementById('crop').addEventListener('hidden.bs.modal', event => {
        window.cropper.destroy();
        window.cropper = null;
    })
</script>
