<div class="modal fade" tabindex="-1" id="report" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report <strong id="type"></strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('user.reports.report')}}" method="POST">
                <div class="modal-body ms-3 mt-3">
                    @foreach($report_reasons as $id => $name)
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="reason" id="{{$id}}" value="{{$id}}" required>
                            <label class="form-check-label" for="{{$id}}">
                                {{$name}}
                            </label>
                        </div>
                    @endforeach
                    <div class="mb-3">
                        <label for="comment" class="form-label">Add more information</label>
                        <textarea class="form-control" id="comment" rows="3" name="comment"></textarea>
                    </div>
                     <input required type="hidden" name="id" value="">
                     <input required type="hidden" name="type" value="">
                    <p class="text-muted text-sm mt-4">
                        Flagged videos and users are reviewed by YouTube staff 24 hours a day, 7 days a week to determine whether they violate Community Guidelines. Accounts are penalized for Community Guidelines violations, and serious or repeated violations can lead to account termination. Report channel
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
        document.getElementById('type').innerText = type;
    })
</script>
