<div class="modal fade" tabindex="-1" id="premium" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" x-data="report">
            <div class="modal-body text-center ms-3 mt-2">
                <img class="img-fluid" src="{{asset('images/premium/success.jpg')}}" alt="Report success">
                <h3 class="my-4">Thanks for your subscription</h3>
                <div class="alert alert-success mt-4">
                    Your are premium until <strong>{{Auth::user()->premium_end->format('d F Y')}}</strong>
                </div>
                <a class="btn btn-success w-100" href="{{route('pages.home')}}">Discover {{config('app.name')}} Premium</a>
            </div>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        new bootstrap.Modal('#premium').show()
    })
</script>