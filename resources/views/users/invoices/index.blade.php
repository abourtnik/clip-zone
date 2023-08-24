@extends('layouts.user')

@section('title', 'My Invoices')

@section('content')
    @if($invoices->total())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>Invoices</h2>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col" style="min-width: 300px;">Date</th>
                    <th scope="col" style="min-width: 300px;">Description</th>
                    <th scope="col" style="min-width: 120px;">Price</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td class="align-middle">
                            {{$invoice->date->format('d F Y')}}
                        </td>
                        <td class="align-middle">
                            Premium account {{$invoice->date->format('d F Y')}}
                        </td>
                        <td class="align-middle">
                            @money($invoice->amount)
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
    @else
        <div class="card shadow">
            <div class="card-body d-flex justify-content-center align-items-center">
                <div class="my-3 text-center">
                    <i class="fa-solid eye-slash fa-2x"></i>
                    <h5 class="my-3">No invoices</h5>
                </div>
            </div>
        </div>
    @endif
@endsection
