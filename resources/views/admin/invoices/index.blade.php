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
                <th>Date</th>
                <th>Amount</th>
                <th>Tax</th>
                <th>Fee</th>
                <th>User</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr class="bg-light">
                    <td class="align-middle">
                        {{$invoice->date->diffForHUmans()}}
                    </td>
                    <td class="align-middle">
                        @money($invoice->amount)
                    </td>
                    <td class="align-middle">
                        @money($invoice->tax)
                    </td>
                    <td class="align-middle">
                        @money($invoice->fee)
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
@endsection