@extends('layouts.admin')

@section('title', 'Plans')

@section('content')
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2>Plans</h2>
    </div>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
            <tr style="border-top: 3px solid #0D6EFD;">
                <th>Name</th>
                <th>Price</th>
                <th>Duration</th>
                <th>Stripe ID</th>
                <th style="min-width: 150px">Created At</th>
            </tr>
            </thead>
            <tbody>
            @foreach($plans as $plan)
                <tr class="bg-light">
                    <td class="align-middle">
                        {{$plan->name}}
                    </td>
                    <td class="align-middle">
                        {{$plan->price}}
                    </td>
                    <td class="align-middle">
                        {{$plan->duration}}
                    </td>
                    <td class="align-middle">
                        {{$plan->stripe_id}}
                    </td>
                    <td class="align-middle">
                        {{$plan->created_at->diffForHUmans()}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
