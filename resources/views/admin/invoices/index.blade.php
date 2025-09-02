@extends('layouts.admin')

@section('title', 'Invoices')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Invoices</h2>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th style="min-width: 110px">Date</th>
                <th style="min-width: 50px">Amount</th>
                <th style="min-width: 50px">Tax</th>
                <th style="min-width: 50px">Fee</th>
                <th style="min-width: 150px">User</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr class="bg-light">
                    <td class="align-middle">
                        <small class="text-muted" data-bs-toggle="tooltip" data-bs-title="{{$invoice->date->format('d F Y - H:i')}}">
                            {{$invoice->date->diffForHumans()}}
                        </small>
                    </td>
                    <td class="align-middle">
                        {{ $invoice->amount }}
                    </td>
                    <td class="align-middle">
                        {{ $invoice->tax }}
                    </td>
                    <td class="align-middle">
                        {{ $invoice->fee }}
                    </td>
                    <td class="align-middle">
                        {{$invoice->user->username}}
                    </td>
                    <td class="align-middle">
                        <div class="d-flex gap-1">
                            <a href="{{$invoice->route}}" target="_blank" class="btn btn-primary btn-sm d-flex align-items-center gap-2" title="Download Invoice">
                                <i class="fa-solid fa-download"></i>
                                <span>Download</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $invoices->links() }}
@endsection
