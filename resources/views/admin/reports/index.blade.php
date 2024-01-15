@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
    @if($reports->total() || $filters)
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Reports</h2>
        </div>
        <hr>
        <form class="my-4 d-flex gap-3 align-items-end" method="GET">
            <div class="col">
                <label for="search" class="form-label fw-bold">Search</label>
                <input type="search" class="form-control" id="search" placeholder="Search" name="search" value="{{$filters['search'] ?? null}}">
            </div>
            <div class="col">
                <search-model name="user" endpoint="{{route('admin.search.users')}}" label="Report by" @isset($selectedUser)) value="{{$selectedUser}}" @endisset/>
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
                        <option @selected(($filters['status'] ?? null) === (string) $id) value="{{$id}}">{{$label}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="date_start" class="form-label fw-bold">Report date start</label>
                <input type="datetime-local" name="date_start" class="form-control" id="date_start" value="{{$filters['date_start'] ?? null}}">
            </div>
            <div class="col">
                <label for="date_end" class="form-label fw-bold">Report date end</label>
                <input type="datetime-local" name="date_end" class="form-control" id="date_end" value="{{$filters['date_end'] ?? null}}">
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
                    <th style="width: 10%">Report By</th>
                    <th style="width: 10%">Type</th>
                    <th class="w-30">Content</th>
                    <th class="w-30">Reason</th>
                    <th >Status</th>
                    <th style="width: 7%">Date</th>
                    <th style="width: 7%">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reports as $report)
                    <tr class="bg-light">
                        <td class="align-middle">
                            <a class="d-flex text-decoration-none align-items-center gap-2" href="{{$report->user->route}}">
                                <img class="rounded" src="{{$report->user->avatar_url}}" alt="{{$report->user->username}} avatar" style="width: 50px;">
                                <div>{{$report->user->username}}</div>
                            </a>
                        </td>
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
                            <div class="badge bg-danger">
                                {{$report->reason->value}}
                            </div>
                            <x-expand-item>
                                {{$report->comment}}
                            </x-expand-item>
                        </td>
                        <td class="align-middle">
                            <div class="badge bg-{{$report->status->color()}}">
                                {{$report->status->name()}}
                            </div>
                        </td>
                        <td class="align-middle text-sm">
                            {{$report->created_at->diffForHumans()}}
                        </td>
                        <td class="align-middle">
                            @if($report->is_pending)
                                <div class="d-flex flex-column gap-2">
                                    <form class="w-100" method="POST" action="{{route('admin.reports.accept', $report)}}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fa-solid fa-check"></i>&nbsp;
                                            Accept
                                        </button>
                                    </form>
                                    <form class="w-100" method="POST" action="{{route('admin.reports.reject', $report)}}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="fa-solid fa-xmark"></i>&nbsp;
                                            Reject
                                        </button>
                                    </form>
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
                    <i class="fa-solid fa-eye-slash fa-2x"></i>
                    <h5 class="my-3">No reports are submitted.</h5>
                </div>
            </div>
        </div>
    @endif
@endsection
