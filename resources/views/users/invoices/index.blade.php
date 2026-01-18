@extends('layouts.user')

@section('title', __('My Invoices'))

@section('content')
    @if($invoices->total())
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2>{{ __('Invoices') }}</h2>
        </div>
        <hr>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr style="border-top: 3px solid #0D6EFD;">
                    <th scope="col" style="min-width: 300px;">{{ __('Date') }}</th>
                    <th scope="col" style="min-width: 300px;">{{ __('Description') }}</th>
                    <th scope="col" style="min-width: 120px;">{{ __('Price') }}</th>
                    <th scope="col">{{ __('Actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td class="align-middle">
                            {{$invoice->date->format('d F Y')}}
                        </td>
                        <td class="align-middle">
                            {{ __('Premium account')}} {{$invoice->date->translatedFormat('d F Y')}}
                        </td>
                        <td class="align-middle">
                            {{ $invoice->amount }}
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1">
                                <a href="{{$invoice->route}}" target="_blank" class="btn btn-primary btn-sm d-flex align-items-center gap-2" title="Download Invoice">
                                    <i class="fa-solid fa-download"></i>
                                    <span>{{ __('Download') }}</span>
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
                    <h5 class="my-3">{{ __('No invoices') }}</h5>
                </div>
            </div>
        </div>
    @endif
@endsection
