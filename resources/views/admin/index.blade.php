@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div x-data="{reportable: {}}">
        <div class="row mb-4">
            <div class="col-12 col-sm-6 col-md-6 col-xl mb-4 mb-xl-0">
                <div class="card shadow border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-center text-primary">Videos</h5>
                        <hr>
                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <i class="fa-solid fa-video fa-2x"></i>
                            <p class="card-text text-center fs-1">{{$videos_count}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xl mb-4 mb-xl-0">
                <div class="card shadow border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-center text-primary">Users</h5>
                        <hr>
                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <i class="fa-solid fa-user fa-2x"></i>
                            <p class="card-text text-center fs-1">{{$users_count}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xl mb-4 mb-sm-0">
                <div class="card shadow border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-center text-primary">Comments</h5>
                        <hr>
                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <i class="fa-solid fa-comment fa-2x"></i>
                            <p class="card-text text-center fs-1">{{$comments_count}}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-6 col-xl mb-4 mb-sm-0">
                <div class="card shadow border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-center text-primary">Reports</h5>
                        <hr>
                        <div class="d-flex align-items-center justify-content-center gap-4">
                            <i class="fa-solid fa-flag fa-2x"></i>
                            <p class="card-text text-center fs-1">{{$reports_count}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex align-items-center gap-3 p-3">
                        <i class="fa-solid fa-flag"></i>
                        <h5 class="card-title mb-0">
                            Most Reported contents
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($reports->count())
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">Type</th>
                                        <th scope="col" class="w-50" style="min-width: 400px">Content</th>
                                        <th scope="col" style="min-width: 150px">First Report</th>
                                        <th scope="col">Count</th>
                                        <th scope="col" style="min-width: 305px">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($reports as $report)
                                        <tr>
                                            <td class="align-middle">
                                                <div class="d-flex align-items-center justify-content-center gap-2 badge bg-info">
                                                    <i class="fa-solid fa-{{$report->icon}}"></i>
                                                    {{$report->type}}
                                                </div>
                                            </td>
                                            <td class="align-middle ps-3">
                                                @if($report->type == 'Video')
                                                    <div class="d-flex gap-3 align-items-start">
                                                        <a href="{{$report->reportable->route}}">
                                                            <img src="{{$report->reportable->thumbnail_url}}" alt="" style="width: 120px;height: 68px">
                                                        </a>
                                                        <div class="d-flex flex-column gap-1">
                                                            <small>{{Str::limit($report->reportable->title, 100), '...'}}</small>
                                                            <small class="text-muted">{{Str::limit($report->reportable->description, 200), '...'}}</small>
                                                            <a class="text-primary text-sm text-decoration-none" href="{{$report->reportable->user->route}}">
                                                                {{$report->reportable->user->username}}
                                                            </a>
                                                        </div>
                                                    </div>
                                                @elseif($report->type == 'Comment')
                                                    <a class="text-primary text-sm text-decoration-none" href="{{$report->reportable->user->route}}">
                                                        {{$report->reportable->user->username}}
                                                    </a>
                                                    <x-expand-item max="400">
                                                        {{$report->reportable->content}}
                                                    </x-expand-item>
                                                @elseif($report->type == 'User')
                                                    <a class="d-flex text-decoration-none align-items-center gap-2" href="{{$report->reportable->route}}">
                                                        <img class="rounded" src="{{$report->reportable->avatar_url}}" alt="{{$report->reportable->username}} avatar" style="width: 50px;">
                                                        <div>{{$report->reportable->username}}</div>
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="align-middle text-sm">
                                                <div data-bs-toggle="tooltip" data-bs-title="{{$report->first_report_at->format('d F Y - H:i')}}">
                                                    {{$report->first_report_at->diffForHumans()}}
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="badge bg-secondary">
                                                    {{trans_choice('reports', $report->reports_count)}}
                                                </div>
                                            </td>
                                            <td class="align-middle text-sm">
                                                <div class="d-flex gap-2 align-items-center">
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-primary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#reports"
                                                    >
                                                        <i class="fa-solid fa-flag"></i>&nbsp;
                                                        See reports
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#block_report"
                                                        data-title="{{$report->reportable->title}}"
                                                        data-poster="{{$report->reportable->thumbnail_url}}"
                                                        data-route="{{route('admin.reports.block', ['type' => strtolower($report->type), 'reportable' => $report->reportable_id])}}"
                                                        @click="reportable = $el.dataset;"
                                                    >
                                                        <i class="fa-solid fa-ban"></i>&nbsp;
                                                        Block
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-sm btn-secondary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#cancel_report"
                                                        data-title="{{$report->reportable->title}}"
                                                        data-poster="{{$report->reportable->thumbnail_url}}"
                                                        data-route="{{route('admin.reports.cancel', ['type' => strtolower($report->type), 'reportable' => $report->reportable_id])}}"
                                                        @click="reportable = $el.dataset;"
                                                    >
                                                        <i class="fa-solid fa-xmark"></i>&nbsp;
                                                        Cancel
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="bg-light">
                                            <td colspan="7" class="text-center">
                                                <i class="fa-solid fa-bell-slash fa-2x my-3"></i>
                                                <p class="fw-bold">No matching reports for this period</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center my-3">
                                <i class="fa-solid fa-eye-slash fa-2x"></i>
                                <h5 class="my-3">No reports yet</h5>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{route('admin.reports.index')}}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-eye"></i>&nbsp;
                                See all reports
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin.reports.modals.reports')
        @include('admin.reports.modals.block')
        @include('admin.reports.modals.cancel')
    </div>
@endsection
