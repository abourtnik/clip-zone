<div class="modal fade" tabindex="-1" id="delete_device" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form :action="device.route" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5
                        class="modal-title"
                        x-text="'{{ __('Delete device :device ?', ['device' => '__DEVICE__']) }}'.replace('__DEVICE__', device.name)"
                    >

                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger mb-0 fw-bold">
                        {{ __('The device will no longer be connected to your account') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">
                        {{ __('Delete') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
