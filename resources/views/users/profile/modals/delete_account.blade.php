<div class="modal fade" tabindex="-1" id="delete_account" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Permanently delete your account ? </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <p class="fw-bold">La suppresion d'un compte est definitive.</p>
                    <p>La suppresion de votre compte entraîne automatiquement la suppresion de toutes vos videos, commentaires et interractions</p>
                    <ul>
                        <li> {{$user->videos->count()}} videos</li>
                        <li> {{$user->comments->count()}} comments</li>
                        <li> {{$user->likes->count()}} likes</li>
                        <li>{{$user->likes->count()}} dislikes</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form method="POST" action="{{route('user.delete', $user)}}">
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
