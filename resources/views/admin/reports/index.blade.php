@use('App\Filters\Forms\Admin\ReportFiltersForm')

@extends('layouts.admin')

@section('title', 'Reports')

@section('content')
    @if($reports->total() || request()->all())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Reports</h2>
        </div>
        <hr>
        {!! form(FormBuilder::create(ReportFiltersForm::class)) !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th style="min-width: 150px">Report By</th>
                    <th style="width: 10%">Type</th>
                    <th class="w-30">Content</th>
                    <th class="w-30">Reason</th>
                    <th>Status</th>
                    <th style="min-width: 150px">Date</th>
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
                            <div class="mt-2">
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
                        <td class="align-middle text-sm">
                            {{$report->created_at->diffForHumans()}}
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
