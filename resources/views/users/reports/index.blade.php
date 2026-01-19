@use('App\Filters\Forms\User\ReportFiltersForm')

@extends('layouts.user')

@section('title', __('My Reports'))

@section('content')
    @if($reports->total() || request()->all())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>{{ __('My Reports') }}</h2>
        </div>
        <hr>
        {!! form(FormBuilder::create(ReportFiltersForm::class)) !!}
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th class="w-10">{{ __('Type') }}</th>
                    <th class="w-35" style="min-width: 350px;">{{ __('Content') }}</th>
                    <th class="w-35">{{ __('Reason') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th style="min-width: 89px;">{{ __('Date') }}</th>
                    <th>{{ __('Action') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reports as $report)
                    <tr class="bg-light">
                        <td class="align-middle">
                            <div class="d-flex align-items-center justify-content-center gap-2 badge bg-info">
                                <i class="fa-solid fa-{{$report->icon}}"></i>
                                {{ __($report->type) }}
                            </div>
                        </td>
                        <td class="align-middle">
                            @include('reports.' .strtolower($report->type))
                        </td>
                        <td class="align-middle">
                            <div class="badge bg-danger mb-2">
                                {{ $report->reason->name() }}
                            </div>
                            <div>
                                <x-expand-item>
                                    {{$report->comment}}
                                </x-expand-item>
                            </div>
                        </td>
                        <td class="align-middle">
                            <div class="badge bg-{{$report->status->color()}}">
                                {{ __($report->status->name()) }}
                            </div>
                        </td>
                        <td class="align-middle">
                            <small data-bs-toggle="tooltip" data-bs-title="{{$report->created_at->format('d F Y - H:i')}}">
                                {{$report->created_at->diffForHumans()}}
                            </small>
                        </td>
                        <td class="align-middle">
                            @if($report->is_pending)
                                <button
                                    data-bs-toggle="modal"
                                    data-bs-target="#cancel-report"
                                    data-route="{{route('user.reports.cancel', $report)}}"
                                    class="btn btn-sm btn-primary d-flex align-items-center gap-1"
                                >
                                    <i class="fa-solid fa-ban"></i>&nbsp;
                                    <span>{{ __('Cancel')}}</span>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">{{ __('No matching reports') }}</p>
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
                    <h5 class="my-3">{{ __('You haven\'t submitted any reports') }}</h5>
                </div>
            </div>
        </div>
    @endif
    @include('users.reports.modals.cancel')
@endsection
