<div class="modal fade" tabindex="-1" id="report" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" x-data="report">
            <div class="modal-header">
                <h5 class="modal-title">Report <strong id="type"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="report-form" method="POST" x-show="!reported">
                @csrf
                <div class="modal-body ms-3 mt-2">
                    @foreach($report_reasons as $id => $name)
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="reason" id="{{$id}}" value="{{$name}}" required>
                            <label class="form-check-label" for="{{$id}}">
                                {{$name}}
                            </label>
                        </div>
                    @endforeach
                    <hr>
                    <div class="mb-3" x-init="count = $refs.comment.value.length">
                        <label for="comment" class="form-label">Provide additional details</label>
                        <textarea
                            class="form-control"
                            id="comment"
                            rows="3"
                            name="comment"
                            maxlength="{{config('validation.report.comment.max')}}"
                            x-ref="comment"
                            @keyup="count = $refs.comment.value.length"
                        ></textarea>
                        <div class="form-text">
                            <span x-text="count"></span> / <span>5000</span>
                        </div>
                    </div>
                     <input required type="hidden" name="id" value="">
                     <input required type="hidden" name="type" value="">
                    <p class="text-muted text-sm mt-4">
                        Reported videos, comments and users are reviewed by {{config('app.name')}} staff 24 hours a day, 7 days a week to determine whether they violate Community Guidelines.
                        You can follow the progress of your reports from your account : <a class="text-decoration-none" href="{{route('user.reports.index')}}">See my reports</a>
                    </p>
                    <div x-show="error" x-text="error" class="alert alert-danger"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button :disabled="loading" type="button" @click="perform()" class="btn btn-primary d-flex align-items-center gap-1">
                        <span x-show="loading" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>
                        <span>Report</span>
                    </button>
                </div>
            </form>
            <div x-show="reported">
                <div class="modal-body text-center ms-3 mt-2">
                    <img class="img-fluid" src="{{asset('images/reports/ok.jpg')}}" alt="Report success">
                    <h3 class="my-4">Thanks for reporting</h3>
                    <p class="text-muted mt-4">
                        If we find this content to be in violation of our Community Guidelines, we will remove it.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('report').addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget

        const id = button.dataset.id;
        const type = button.dataset.type;

        document.querySelector('input[name="id"]').value = id;
        document.querySelector('input[name="type"]').value = type;
        document.getElementById('type').innerText = type.replace("App\\Models\\", '');
    })

    document.addEventListener('alpine:init', () => {
        Alpine.data('report', () => ({
            init() {
                document.getElementById('report').addEventListener('hide.bs.modal', event => {
                    document.getElementById('report-form').reset()
                    this.reported = false
                    this.count = 0
                })
            },
            loading: false,
            reported: false,
            error: null,
            count : 0,
            async perform () {
                this.loading = true;
                this.error = null;
                const response = await fetch('{{route('report')}}', {
                    method: 'POST',
                    body: new FormData(document.getElementById('report-form')),
                    headers: {
                        "Accept": "application/json",
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                });
                this.loading = false;
                if (response.ok) {
                    this.reported = true;
                } else {
                    const data = await response.json();
                    this.error = data?.message ?? 'Whoops! An error occurred. Please try again later.';
                }
            }
        }));
    })
</script>
