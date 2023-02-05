<div class="modal fade" tabindex="-1" id="delete_account" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
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
                        <li>{{trans_choice('likes', $user->likes->count())}}</li>
                        <li>{{trans_choice('dislikes', $user->dislikes->count())}}</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="{{route('user.delete')}}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fa-solid fa-trash"></i> &nbsp;
                        Delete your account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
