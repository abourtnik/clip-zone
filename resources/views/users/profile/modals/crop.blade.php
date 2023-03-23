<div class="modal modal-lg fade" tabindex="-1" id="crop" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customize picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img style="display: block;max-width: 100%;" id="image" src="">
                </div>
            </div>
            <div class="modal-footer" x-data="{}">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="crop_button" type="button" class="btn btn-primary">Done</button>
            </div>
        </div>
    </div>
</div>

<script>

    document.getElementById('crop').addEventListener('shown.bs.modal', event => {

        const image = document.getElementById('image');

        const cropper = new Cropper(image, {
            aspectRatio : 1,
            viewMode: 3,
            modal: true,
            background: true,
            center: true,
            autoCropArea: 1,
            dragMode: 'move',
            responsive: true,
            guides : false
        });

        window.cropper = cropper;


        document.getElementById('crop_button').addEventListener('click', e => {

            const blob = cropper.getCroppedCanvas().toDataURL();
            //document.getElementById('avatar').setAttribute('src', cropper.getCroppedCanvas().toDataURL())

            console.log(blob);

            const file = new File([blob], "avatar.png", {type:"image/png", lastModified:new Date().getTime()});

            console.log(file);

            let container = new DataTransfer();
            container.items.add(file);


            document.getElementById('avatar_input').files = container.files;

            //modal.hide()
        })
    })

    document.getElementById('crop').addEventListener('hidden.bs.modal', event => {

        cropper.destroy();
    })



</script>

<style>
    .cropper-view-box,
    .cropper-face {
        border-radius: 50%;
    }
</style>

