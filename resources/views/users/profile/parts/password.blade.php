<div class="row align-items-start my-3">
    <div class="col-12 col-xl-4">
        <h2>{{ __('Update Password') }}</h2>
        <p class="text-muted">{{ __('Ensure your account using a long, random password to stay secure') }}</p>
    </div>
    <div class="col-12 col-xl-8">
        <div class="card shadow-soft my-3">
            <form action="{{ route('user.update.password') }}" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                            <div class="input-group mb-3" x-data="{show:false}">
                                <input :type="show ? 'text' : 'password'" class="form-control" id="current_password" name="current_password" required>
                                <button type="button" class="btn btn-outline-secondary border" @click="show=!show">
                                    <i x-show="!show" class="fa-solid fa-eye"></i>
                                    <i x-show="show" class="fa-solid fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="new_password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group mb-3" x-data="{show:false}">
                                <input :type="show ? 'text' : 'password'" class="form-control" id="new_password" name="new_password" required>
                                <button type="button" class="btn btn-outline-secondary border" @click="show=!show">
                                    <i x-show="!show" class="fa-solid fa-eye"></i>
                                    <i x-show="show" class="fa-solid fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 mb-3">
                            <label for="new_password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                            <div class="input-group mb-3" x-data="{show:false}">
                                <input :type="show ? 'text' : 'password'" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                <button type="button" class="btn btn-outline-secondary border" @click="show=!show">
                                    <i x-show="!show" class="fa-solid fa-eye"></i>
                                    <i x-show="show" class="fa-solid fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fa-solid fa-user-edit"></i>
                        {{ __('Update Password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
