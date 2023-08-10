@extends('layouts.default')

@section('title', 'Premium')

@section('content')
    <div class="row align-items-center h-75 mt-5">
        <div class="col-10 offset-1 border border-1 bg-light py-4 px-3 px-sm-4" x-data="{duration : 'daily'}">
            @if (session('error'))
                <div class="alert alert-danger">
                    {!! session('error') !!}
                </div>
            @endif
            <h1 class="mb-3 text-center fw-bold">
                <span class="text-danger">{{config('app.name')}}</span>
                <span class="text-warning">Premium</span>
            </h1>
            <h2 class="my-5 text-center">Unleash the full potential of your creative journey with our premium plan</h2>
            <div class="row">
                <div class="col-12 col-lg-6 col-xl-5 mb-3 mb-lg-0">
                    <div class="mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5>Billing</h5>
                                <hr>
                                <div class="d-flex gap-2 mb-3">
                                    @foreach($plans as $plan)
                                        <button
                                            :class="duration === '{{$plan->name}}' ? 'btn-primary' : 'btn-outline-primary'"
                                            class="btn w-100"
                                            @click="duration = '{{$plan->name}}'"
                                        >
                                            {{Str::ucfirst($plan->name)}}
                                        </button>
                                    @endforeach
                                </div>
                                <small class="text-muted">Save 2 months with the annual subscription</small>
                            </div>
                        </div>
                    </div>
                    @foreach($plans as $plan)
                        <div class="card shadow" x-show="duration === '{{$plan->name}}'">
                            <div class="card-body">
                                <h5 class="card-title fs-1 text-primary text-center">{{$plan->price}} € / {{\Carbon\CarbonInterval::days($plan->duration)->forHumans(['join' => fn($a) => explode(' ', $a[0])[1]])}}</h5>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex align-items-center gap-4 py-3">
                                    <i class="fa-solid fa-upload fa-2x text-danger"></i>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold">Uploads</div>
                                        Unlimited videos uploads and storage space
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center gap-4 py-3">
                                    <i class="fa-solid fa-download fa-2x text-danger"></i>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold">Downloads</div>
                                        Save videos for when you really need them – like when you’re on a plane or commuting.
                                    </div>
                                </li>
                                <li class="list-group-item d-flex align-items-center gap-4 py-3">
                                    <i class="fa-solid fa-user-check fa-2x text-danger"></i>
                                    <div class="d-flex flex-column gap-1">
                                        <div class="fw-bold">Channel promotion</div>
                                        Promote your channel in discovery page
                                    </div>
                                </li>
                            </ul>
                            <div class="card-body text-center py-3">
                                @if(Auth::user()->is_premium)
                                    <div class="alert alert-success">
                                        You already have an active subscription until <strong>{{Auth::user()->premium_end->format('d F Y')}}</strong>
                                    </div>
                                @else
                                    <a href="{{route('premium.subscribe', $plan)}}" class="btn btn-primary fs-5 w-100">Start Trial</a>
                                    <div class="text-sm text-muted mt-3">
                                        You will be charged automatically at the end of each period.
                                        Payments won't be refunded for partial billing periods. Cancel anytime from your account.
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="card h-100">
                        <div class="card-header text-center h5">
                            Plans comparison
                        </div>
                        <div class="card-body">
                            <table class="table" style="--table-cell-padding-y:.6rem">
                                <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Free plan</th>
                                    <th scope="col">Premium plan</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="py-4">
                                    <td class="fw-bold">Max Video Uploads</td>
                                    <td>{{config('plans.free.max_uploads')}}</td>
                                    <td>Unlimited</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Max File Size Per Upload</td>
                                    <td>@size(config('plans.free.max_file_size'))</td>
                                    <td>@size(config('plans.premium.max_file_size'))</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Video Storage Space</td>
                                    <td>@size(config('plans.free.max_videos_storage'))</td>
                                    <td>Unlimited</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Downloads</td>
                                    <td>
                                        <i class="fa-solid fa-xmark fs-4 text-gray-500"></i>
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-check fs-4 text-black"></i>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Channel promotion</td>
                                    <td>
                                        <i class="fa-solid fa-xmark fs-4 text-gray-500"></i>
                                    </td>
                                    <td>
                                        <i class="fa-solid fa-check fs-4 text-black"></i>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeWhen($success, 'modals.premium')
@endsection
