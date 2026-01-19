<div class="row align-items-start my-3">
    <div class="col-12 col-xl-4">
        <h2 class="text-danger">{{ __('Delete Account') }}</h2>
        <p class="text-muted">{{ __('Permanently delete your account') }}</p>
    </div>
    <div class="col-12 col-xl-8">
        <div class="card shadow-soft my-3">
            <div class="card-body">
                <div class="alert alert-danger fw-bold">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download all data or information that you wish to retain.') }}
                </div>
            </div>
            <div class="card-footer d-flex justify-content-end">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_account">
                    <i class="fa-solid fa-user-times"></i>
                    {{ __('Delete account') }}
                </button>
            </div>
        </div>
    </div>
</div>
