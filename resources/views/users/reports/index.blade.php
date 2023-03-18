@extends('layouts.user')

@section('title', 'Channel Reports')

@section('content')
    @if($reports->total() || $filters)
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>My Reports</h2>
        </div>
        <hr>
        <form class="my-4 d-flex gap-3 align-items-end" method="GET">
            <div class="col">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col">
                <label for="type" class="form-label fw-bold">Type</label>
                <select name="type" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach(['video', 'comment', 'user'] as $option)
                        <option @selected(($filters['type'] ?? null) === $option) value="{{$option}}">{{ucfirst($option)}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="reason" class="form-label fw-bold">Reason</label>
                <select name="reason" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($reasons as $id => $label)
                        <option @selected(($filters['reason'] ?? null) === $label) value="{{$label}}">{{$label}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="status" class="form-label fw-bold">Status</label>
                <select name="status" class="form-select" aria-label="Default select example">
                    <option selected value="">All</option>
                    @foreach($status as $id => $label)
                        <option @selected(($filters['status'] ?? null) === $id) value="{{$id}}">{{$label}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="report_date_start" class="form-label fw-bold">Report date start</label>
                <input type="datetime-local" name="date[]" class="form-control" id="report_date_start" value="{{$filters['date'][0] ?? null}}">
            </div>
            <div class="col">
                <label for="report_date_end" class="form-label fw-bold">Report date end</label>
                <input type="datetime-local" name="date[]" class="form-control" id="report_date_end" value="{{$filters['date'][1] ?? null}}">
            </div>
            <div class="btn-group">
                <button type="submit" class="btn btn-outline-secondary" title="Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                <a href="?clear=1" class="btn btn-outline-secondary" title="Clear">
                    <i class="fa-solid fa-eraser"></i>
                </a>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th style="width: 10%">Type</th>
                    <th class="w-35">Content</th>
                    <th class="w-35">Reason</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reports as $report)
                    <tr class="bg-light">
                        <td class="align-middle">
                            <div class="d-flex align-items-center justify-content-center gap-2 badge bg-info">
                                <i class="fa-solid fa-{{$report->icon}}"></i>
                                {{$report->type}}
                            </div>
                        </td>
                        <td class="align-middle">
                            @include('reports.' .strtolower($report->type))
                        </td>
                        <td class="align-middle">
                            <div class="badge bg-danger mb-2">
                                {{$report->reason->value}}
                            </div>
                            <div>
                                <x-expand-item>
                                    {{$report->comment}}
                                </x-expand-item>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="badge bg-{{$report->status->color()}}">
                                {{$report->status->name()}}
                            </div>
                        </td>
                        <td class="align-middle">
                            <small data-bs-toggle="tooltip" data-bs-title="{{$report->created_at->format('d F Y - H:i')}}">
                                {{$report->created_at->diffForHumans()}}
                            </small>
                        </td>
                        <td class="align-middle">
                            @if($report->is_pending)
                                <div class="d-flex align-items-center gap-2">
                                    <button
                                        data-bs-toggle="modal"
                                        data-bs-target="#cancel-report"
                                        data-route="{{route('user.reports.cancel', $report)}}"
                                        class="btn btn-sm btn-primary"
                                    >
                                        <i class="fa-solid fa-ban"></i>&nbsp;
                                        Cancel
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">No matching reports</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $reports->links() }}
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-flag fa-2x"></i>
                    <h5 class="my-3">You haven't submitted any reports.</h5>
                </div>
            </div>
        </div>
    @endif
    @include('users.reports.modals.cancel')
@endsection
