<div class="modal fade" tabindex="-1" id="premium" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-5">
                        <img class="img-fluid" src="{{asset('images/premium/success.jpg')}}" alt="Premium welcome">
                    </div>
                    <div class="col-12 col-lg-7 text-center text-lg-start">
                        <h3 class="my-4">Welcome to {{config('app.name')}} Premium !</h3>
                        <p class="text-muted">Your membership is now active and benefits start immediately.</p>
                        <p class="text-muted">An email receipt has been sent to {{Auth::user()?->email}}.</p>
                        <p class="text-muted">Your membership information is available in your <a class="text-decoration-none" href="{{route('user.edit')}}">account</a>.</p>
                        <div class="text-end mt-5">
                            <button type="button" class="btn-link btn text-decoration-none fw-bold" data-bs-dismiss="modal" aria-label="Close">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        new bootstrap.Modal('#premium').show()
    })
</script>
