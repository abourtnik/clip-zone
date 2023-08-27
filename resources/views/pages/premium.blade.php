@extends('layouts.default')

@section('title', 'Premium')

@section('class', 'ms-0')

@section('content')
    <div class="row align-items-center h-75 mt-5">
        <div class="col-10 offset-1 border border-1 bg-light py-4 px-3 px-sm-4" x-data="{plan : 1}">
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
            <h3 class="my-5 text-center">
                <span>{{config('plans.trial_period.period')}} days trial • Then</span>
                @foreach($plans as $plan)
                    <span x-show="plan === {{$plan->id}}">{{$plan->price}} € / {{$plan->period}} </span>
                @endforeach
                <span>• Cancel anytime</span>
            </h3>
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
                                            :class="plan === {{$plan->id}} ? 'btn-primary' : 'btn-outline-primary'"
                                            class="btn w-100"
                                            @click="plan = {{$plan->id}}"
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
                        <div class="card shadow" x-show="plan === {{$plan->id}}">
                            <div class="card-body">
                                <h5 class="card-title fs-1 text-primary text-center">{{$plan->price}} € / {{$plan->period}}</h5>
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
                                @if(Auth::user()?->premium_subscription)
                                    <div class="alert alert-success">
                                        You already have an subscription, please manage it on your <a href="{{route('user.edit')}}">account</a>.
                                    </div>
                                @else
                                    <a href="{{route('premium.subscribe', $plan)}}" class="btn btn-primary fs-5 w-100">Start Trial</a>
                                    <div class="text-sm text-muted mt-3">
                                        <p class="fw-bold">Billing starts on {{now()->add('days', config('plans.trial_period.period'))->format('j F Y')}}, the end of your trial, and will renew automatically every {{$plan->period}}.</p>
                                        You will be charged automatically at the end of each period.
                                        Payments won't be refunded for partial billing periods. Cancel anytime from your account.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="col-12 col-lg-6 col-xl-7">
                    <div class="card card-body bg-light mb-3 h-50">
                        <div class="d-flex gap-2 step">
                            <div class="progression">
                                <div class="icon bg-success">
                                    <i class="fa fa-gift text-white"></i>
                                </div>
                                <div class="line">
                                    <div class="half-line bg-success"></div>
                                </div>
                            </div>
                            <div>
                                <p class="fw-bold mb-1">Today: Free trial for {{config('plans.trial_period.period')}} days. Cancel anytime.</p>
                                <small class="text-muted text-sm">Start your free trial of {{config('app.name')}} Premium and enjoy all premium features.</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 step">
                            <div class="progression">
                                <div class="icon bg-white border border-secondary">
                                    <i class="far fa-bell"></i>
                                </div>
                                <div class="line"></div>
                            </div>
                            <div>
                                <p class="fw-bold mb-1">{{now()->add('days', config('plans.trial_period.email_reminder'))->format('j F Y')}}</p>
                                <small class="text-muted">We'll send you a reminder email {{config('plans.trial_period.email_reminder')}} days before your {{config('app.name')}} Premium trial ends.</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 step">
                            <div class="progression">
                                <div class="icon bg-white border border-secondary">
                                    <i class="fa fa-star text-warning"></i>
                                </div>
                            </div>
                            <div>
                                <p class="fw-bold mb-1">{{now()->add('days', config('plans.trial_period.period'))->format('j F Y')}}</p>
                                <small class="text-muted">Your subscription begins unless you canceled it during the trial period.</small>
                            </div>
                        </div>
                    </div>
                    <div class="card h-50">
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
@endsection
