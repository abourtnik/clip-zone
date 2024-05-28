@extends('layouts.admin')

@section('title', 'Exports')

@section('content')
    @if($exports->total())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Exports</h2>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th style="min-width: 200px">Export</th>
                    <th>Size</th>
                    <th>Status</th>
                    <th style="min-width: 125px">Date</th>
                    <th style="min-width: 121px">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($exports as $export)
                    <tr class="bg-light">
                        <td class="align-middle">
                            {{$export->file}}
                        </td>
                        <td class="align-middle">
                            @size($export->size)
                        </td>
                        <td class="align-middle">
                            <span class="badge bg-{{$export->status->color()}}">
                                <i class="fa-solid fa-{{$export->status->icon()}}"></i>&nbsp;
                                {{$export->status->name()}}
                            </span>
                        </td>
                        <td class="align-middle">
                            {{$export->updated_at->diffForHUmans()}}
                        </td>
                        <td class="align-middle">
                            @if($export->is_completed)
                                <a href="{{route('admin.exports.download', $export)}}" class="btn btn-sm btn-primary" title="Download export">
                                    <i class="fa-solid fa-download"></i>&nbsp;
                                    Download
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">
                            <i class="fa-solid fa-database fa-2x my-3"></i>
                            <p class="fw-bold">No matching exports</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        {{ $exports->links() }}
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid fa-file fa-2x"></i>
                    <h5 class="my-3">No exports are generated</h5>
                </div>
            </div>
        </div>
    @endif
@endsection
