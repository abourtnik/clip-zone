<div class="modal fade" tabindex="-1" id="delete_account" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{route('user.delete')}}">
                <div class="modal-header">
                    <h5 class="modal-title">Permanently delete your account ? </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <p class="fw-bold">Your account will be permanently deleted.</p>
                        <p class="fw-bold">
                            Once your account is deleted, all of its resources and data will be permanently deleted.
                           Before deleting your account, please download all data or information that you wish to retain.
                        </p>
                        <p>This data will be permanently deleted : </p>
                        <ul>
                            <li>{{trans_choice('videos', $user->videos->count())}}</li>
                            <li>{{trans_choice('comments', $user->comments->count())}}</li>
                            <li>{{trans_choice('playlists', $user->playlists->count())}}</li>
                        </ul>
                    </div>
                    <div class="w-100">
                        <label for="delete_current_password" class="form-label">Enter your password for confirm account deletion</label>
                        <input type="password" class="form-control" id="delete_current_password" name="current_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i> &nbsp;
                            Delete your account
                        </button>
                </div>
            </form>
        </div>
    </div>
</div>
