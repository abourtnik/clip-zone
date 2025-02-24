@extends('layouts.user')

@section('title', 'Channel settings')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <p class="fw-bold">Oups some fields are incorrect</p>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if (session('status'))
        <div class="alert alert-success fade show" role="alert">
            <strong>{!! session('status') !!}</strong>
        </div>
    @endif
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Profile Information</h2>
            <p class="text-muted">Update you account profile informations and email address.</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <div @class(["d-flex justify-content-between align-items-center", "flex-column" => $user->premium_subscription, 'flex-column flex-lg-row' => !$user->premium_subscription])>
                            <div class="d-flex align-items-center justify-content-start gap-5">
                                <div
                                    class="position-relative overflow-hidden rounded-circle border border-secondary"
                                    x-data="{hover:false}"
                                    @mouseover="hover=true"
                                    @mouseleave="hover=false"
                                    @cropped-avatar.window="$refs.image.setAttribute('src', URL.createObjectURL($event.detail))"
                                    style="width: 150px; height: 150px"
                                >
                                    <img x-ref="image" class="img-fluid w-100" src="{{$user->avatar_url}}" alt="Avatar" >
                                    <div
                                        class="position-absolute w-100 text-center text-white start-50 z-2"
                                        :class="hover ? 'opacity-100 top-50' : 'opacity-0 top-75'"
                                        style="transition: all 0.3s ease-in-out 0s;transform: translate(-50%, -50%);"
                                    >
                                        <div class="fw-bold">Click to update avatar</div>
                                    </div>
                                    <image-upload
                                        name="avatar"
                                        config="avatar"
                                        class="position-absolute bottom-0 left-0 right-0 top-0 opacity-0 cursor-pointer w-100 z-3"
                                    ></image-upload>
                                    <div
                                        style="transition: all 0.4s ease-in-out 0s;"
                                        class="position-absolute bg-dark bg-opacity-75 bottom-0 left-0 right-0 top-0 w-100"
                                        :class="hover ? 'opacity-100' : 'opacity-0'"
                                    >
                                    </div>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    <div class="text-muted">Member since {{$user->created_at->longAbsoluteDiffForHumans()}}</div>
                                    @if($user->is_premium)
                                        <div class="badge bg-warning">
                                            <i class="fa-solid fa-star"></i>
                                            <span>Premium account</span>
                                        </div>
                                    @else
                                        <div class="badge bg-secondary">Standard account</div>
                                    @endif
                                </div>
                            </div>
                            @if($user->premium_subscription)
                                <hr class="w-100">
                                <div class="alert alert-info w-100">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">My subscription : {{$user->premium_subscription->plan->price}}  € / {{$user->premium_subscription->plan->period}}</div>
                                        <div>
                                            <span>Status :</span>
                                            <strong @class(['text-danger' => !$user->premium_subscription->is_active, 'text-success' => $user->premium_subscription->is_active ])>
                                                {{$user->premium_subscription->status}}
                                            </strong>
                                        </div>
                                    </div>
                                    <hr>
                                    @if($user->premium_subscription->is_canceled)
                                        <p>Your subscription was canceled the <strong>{{ $user->premium_subscription->ends_at->format('d F Y') }}</strong></p>
                                        <a class="btn btn-primary" href="{{route('pages.premium')}}">
                                            Renew my subscription
                                        </a>
                                    @elseif($user->premium_subscription->is_trial_canceled)
                                        <p>You have canceled your subscription</p>
                                        <p>It will be automatically suspended after the : <strong>{{ $user->premium_subscription->ends_at->format('d F Y') }}</strong>.</p>
                                        <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                            Reactivate my subscription
                                        </a>
                                    @elseif($user->premium_subscription->on_trial)
                                        <p>You are currently on trial period until {{$user->premium_subscription->trial_ends_at->format('d F Y')}}</p>
                                        <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                            Cancel my subscription
                                        </a>
                                    @elseif($user->premium_subscription->is_unpaid)
                                        <p class="text-danger fw-bold">Your last payment the <strong>{{$user->premium_subscription->next_payment->subDay()->format('d F Y')}}</strong> was unsuccessful.</p>
                                        <p class="text-danger fw-bold">Please update your payment method to continue to profit premium features.</p>
                                        <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                            Update payment information
                                        </a>
                                    @else
                                        <p>Your next bank debit will be on : <strong>{{ $user->premium_subscription->next_payment->format('d F Y') }}</strong></p>
                                        <a class="btn btn-primary" href="{{$billing_profile_url}}">
                                            Manage my subscription
                                        </a>
                                    @endif
                                </div>
                            @else
                                <hr class="w-100 d-block d-lg-none">
                                <a class="btn btn-warning text-white fw-bold d-flex align-items-center gap-2" href="{{route('pages.premium')}}">
                                    <i class="fa-solid fa-star"></i>
                                    <span>Become premium</span>
                                </a>
                                <div></div>
                            @endif
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="username"
                                    name="username"
                                    required
                                    value="{{old('username', $user->username)}}"
                                    minlength="{{config('validation.user.username.min')}}"
                                    maxlength="{{config('validation.user.username.max')}}"
                                >
                            </div>
                            <div
                                class="col-12 col-sm-6 mb-3"
                                x-data="{ slug: '{{  $user->slug }}', valid :true }"
                            >
                                <label for="slug" class="form-label">Handle</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text">@</span>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :class="!valid && 'is-invalid'"
                                        id="slug"
                                        name="slug"
                                        required
                                        value="{{old('slug', $user->slug)}}"
                                        minlength="{{config('validation.user.slug.min')}}"
                                        maxlength="{{config('validation.user.slug.max')}}"
                                        x-ref="slug"
                                        @keyup="slug = $refs.slug.value; valid = /^[a-zA-Z0-9._-]+$/.test($refs.slug.value)"
                                        pattern="^[a-zA-Z0-9._\-]+$"
                                        title="Handle can only contain letters, numbers, periods (.), dashes (-), and underscores (_)"
                                    >
                                    <div class="invalid-feedback" x-show="!valid">
                                        Handle can only contain letters, numbers, periods (.), dashes (-), and underscores (_)
                                    </div>
                                </div>
                                <div class="form-text" x-show="valid">
                                    <span>{{ config('app.url') }}/@</span><span x-text="slug"></span>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required value="{{old('email', $user->email)}}">
                            </div>
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="country" class="form-label">Country</label>
                                <select class="form-control" name="country" id="country">
                                    <option selected value="">Select Country</option>
                                    @foreach($countries as $code => $name)
                                        <option @selected(old('country', $user->country) === $code) value="{{$code}}">{{$name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-user-edit"></i>
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Channel Informations</h2>
            <p class="text-muted">Update you channel informations</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <form action="{{ route('user.update') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @method('PUT')
                        @csrf
                        <div
                            class="position-relative overflow-hidden"
                            x-data="{hover:false}"
                            @mouseover="hover=true"
                            @mouseleave="hover=false"
                            @cropped-banner.window="$refs.image.setAttribute('src', URL.createObjectURL($event.detail))"
                        >
                            <img x-ref="image" class="img-fluid w-100" src="{{$user->banner_url}}" alt="Banner" >
                            <div
                                class="position-absolute w-100 text-center text-white start-50 z-2"
                                :class="hover ? 'opacity-100 top-50' : 'opacity-0 top-75'"
                                style="transition: all 0.3s ease-in-out 0s;transform: translate(-50%, -50%);"
                            >
                                <div class="fw-bold">Click to update Banner</div>
                            </div>
                            <image-upload
                                name="banner"
                                config="banner"
                                class="position-absolute bottom-0 left-0 right-0 top-0 opacity-0 cursor-pointer w-100 z-3"
                            >
                            </image-upload>
                            <div
                                style="transition: all 0.4s ease-in-out 0s;"
                                class="position-absolute bg-dark bg-opacity-75 bottom-0 left-0 right-0 top-0 w-100"
                                :class="hover ? 'opacity-100' : 'opacity-0'"
                            >
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="website"
                                    name="website"
                                    value="{{old('website', $user->website)}}"
                                    maxlength="{{config('validation.user.website.max')}}"
                                >
                            </div>
                        </div>
                        <div class="row" x-data="{ count: 0 }" x-init="count = $refs.message.value.length">
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Channel Description</label>
                                <textarea
                                    class="form-control"
                                    id="description"
                                    rows="6"
                                    name="description"
                                    maxlength="{{config('validation.user.description.max')}}"
                                    x-ref="message"
                                    @keyup="count = $refs.message.value.length"
                                >{{old('description', $user->description)}}</textarea>
                                <div class="form-text">
                                    <span x-text="count"></span> / <span>{{config('validation.user.description.max')}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check form-switch">
                                    <input type="hidden" value="0" name="show_subscribers">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="show_subscribers"
                                        value="1"
                                        name="show_subscribers"
                                        @checked(old('show_subscribers', $user->show_subscribers))
                                    >
                                    <label class="form-check-label" for="show_subscribers">
                                        Show subscribers count
                                    </label>
                                </div>
                                <div class="form-text">By disabling this option, you don't appear on <a class="text-decoration-none" href="{{route('subscription.discover')}}">discover</a> page</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a class="btn btn-success d-flex align-items-center gap-2" href="{{$user->route}}">
                            <i class="fa-solid fa-eye d-none d-sm-block"></i>
                            <span>Show my channel</span>
                        </a>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-edit d-none d-sm-block"></i>
                            <span>Update Channel</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Social Connection</h2>
            <p class="text-muted">Link your account to a social network to use as a sign-in method</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <div class="card-body d-flex gap-2">
                    @if($user->facebook_id)
                        <a class="btn w-100 text-white text-center position-relative" href="{{route('oauth.unlink', ['service' => 'facebook'])}}" style="background-color: #47639e">
                            <i class="fa-brands fa-facebook-f position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                            <span>Unlink your Facebook account</span>
                        </a>
                    @else
                        <a class="btn w-100 text-white text-center position-relative" href="{{route('oauth.connect', ['service' => 'facebook'])}}" style="background-color: #47639e">
                            <i class="fa-brands fa-facebook-f position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                            <span>Link your Facebook account</span>
                        </a>
                    @endif
                    @if($user->google_id)
                        <a class="btn w-100 text-white text-center position-relative" href="{{route('oauth.unlink', ['service' => 'google'])}}" style="background-color: #dd4b39">
                            <i class="fa-brands fa-google position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                            <span>Unlink your Google account</span>
                        </a>
                    @else
                        <a class="btn w-100 text-white text-center position-relative" href="{{route('oauth.connect', ['service' => 'google'])}}" style="background-color: #dd4b39">
                            <i class="fa-brands fa-google position-absolute top-50 left-5 translate-middle d-none d-sm-block"></i>
                            <span>Link your Google account</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Connected Devices</h2>
            <p class="text-muted">See devices using your account</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <div class="card-body">
                    @if($user->devices->isEmpty())
                        <div class="alert alert-primary mb-0">
                            You haven't connected any device yet
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col" style="min-width: 200px">Device</th>
                                    <th scope="col" style="min-width: 120px">Last activity</th>
                                    <th scope="col" style="min-width: 120px">Created at</th>
                                    <th scope="col" style="min-width: 200px">Expires at</th>
                                    <th scope="col" style="min-width: 6px">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->devices as $device)
                                    <tr>
                                        <td>
                                            <dynamic-input value="{{$device->name}}" name="name" url="{{route('devices.update', $device)}}" maxlength="50" />
                                        </td>
                                        <td>{{$device->last_used_at?->diffForHumans()}}</td>
                                        <td>{{$device->created_at->diffForHumans()}}</td>
                                        <td>{{$device->expires_at?->format('d F Y H:i') ?? 'Never'}}</td>
                                        <td>
                                            <button
                                                class="btn btn-danger btn-sm"
                                                title="Delete device"
                                                data-bs-toggle="modal"
                                                data-bs-target="#delete_device"
                                                data-route="{{route('devices.delete', $device)}}"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2>Update Password</h2>
            <p class="text-muted">Ensure your account using a long, random password to stay secure.</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <form action="{{ route('user.update-password') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="current_password" class="form-label">Current password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="new_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="col-12 col-sm-6 mb-3">
                                <label for="new_password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-secondary">
                            <i class="fa-solid fa-user-edit"></i>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row align-items-start my-3">
        <div class="col-12 col-xl-4">
            <h2 class="text-danger">Delete Account</h2>
            <p class="text-muted">Permanently delete your account</p>
        </div>
        <div class="col-12 col-xl-8">
            <div class="card shadow-soft my-3">
                <div class="card-body">
                    <div class="alert alert-danger fw-bold">
                        Once your account is deleted, all of its resources and data will be permanently deleted.
                        Before deleting your account, please download all data or information that you wish to retain.
                    </div>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete_account">
                        <i class="fa-solid fa-user-times"></i>
                        Delete account
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('users.profile.modals.delete_account', $user)
    @include('users.profile.modals.delete_device')
    @include('users.partials.crop')
@endsection
