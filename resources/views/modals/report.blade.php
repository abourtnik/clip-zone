<div class="modal fade" tabindex="-1" id="report" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report <strong id="type"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.reports.report')}}" method="POST">
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
                    <div class="mb-3" x-data="{ count: 0 }" x-init="count = $refs.comment.value.length">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-regular fa-flag"></i>&nbsp;
                        Report
                    </button>
                </div>
            </form>
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
</script>
