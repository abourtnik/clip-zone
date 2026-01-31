@use('Illuminate\Support\Facades\Auth')

<div class="modal fade" tabindex="-1" id="premium" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-5">
                        <img class="img-fluid" src="{{asset('images/premium/success.jpg')}}" alt="Premium welcome">
                    </div>
                    <div class="col-12 col-lg-7 text-center text-lg-start">
                        <h3 class="my-4">{{ __('Welcome to :app_name Premium !', ['app_name' => config('app.name')]) }}</h3>
                        <p class="text-muted">{{ __('Your membership is now active and benefits start immediately') }}</p>
                        <p class="text-muted">{!! __('An email receipt has been sent to <span class="fw-bold">:email<span>', ['email' => Auth::user()->email]) !!}</p>
                        <p class="text-muted">{!! __('Your membership information is available in your <a class="text-decoration-none" href=":account_url">account</a>', ['account_url' => route('user.edit')]) !!}</p>
                        <div class="text-end mt-5">
                            <button type="button" class="btn-link btn text-decoration-none fw-bold" data-bs-dismiss="modal" aria-label="Close">{{ __('Done') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal('#premium').show()
    })
</script>
