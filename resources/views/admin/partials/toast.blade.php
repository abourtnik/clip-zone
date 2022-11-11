<div
    id="toast-{{$id}}"
    class="toast align-items-center bg-light"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-autohide="true"
    data-bs-animation="true"
    data-bs-delay="15000"
>
    <div class="toast-body">
        <div class="d-flex">
            <div>Votre export des <strong id="toast-model">{{$model}}</strong> est disponible !</div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="mt-2 pt-2 border-top">
            <a id="download-link" href="{{route('admin.download', ['model' => $model])}}" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-download"></i>&nbsp
                Télécharger
            </a>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="toast">Fermer</button>
        </div>
    </div>
</div>
